<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Setting;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Webhook;

class StripePaymentService
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
        
        // Set Stripe API key
        $secretKey = Setting::get('stripe_secret_key');
        if ($secretKey) {
            Stripe::setApiKey($secretKey);
        }
    }

    /**
     * Create a payment intent for an appointment
     */
    public function createPaymentIntent(Appointment $appointment, float $tipAmount = 0): array
    {
        $appointment->load(['service', 'provider', 'salon']);
        
        $serviceAmount = $appointment->service->price;
        $totalAmount = $serviceAmount + $tipAmount;

        // Amount in cents
        $amountInCents = (int) ($totalAmount * 100);

        try {
            $paymentIntent = PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => 'usd',
                'metadata' => [
                    'appointment_id' => $appointment->id,
                    'provider_id' => $appointment->provider_id,
                    'salon_id' => $appointment->salon_id,
                    'service_amount' => $serviceAmount,
                    'tip_amount' => $tipAmount,
                    'total_amount' => $totalAmount,
                ],
                'description' => "Payment for {$appointment->service->name} with {$appointment->provider->name}",
            ]);

            return [
                'success' => true,
                'client_secret' => $paymentIntent->client_secret,
                'payment_intent_id' => $paymentIntent->id,
                'amount' => $totalAmount,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function handleWebhook(string $payload, string $signature): array
    {
        $webhookSecret = Setting::get('stripe_webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Webhook signature verification failed',
            ];
        }

        // Handle the event
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentIntentSucceeded($event->data->object);
                break;
            
            case 'payment_intent.payment_failed':
                $this->handlePaymentIntentFailed($event->data->object);
                break;
                
            default:
                // Unhandled event type
                break;
        }

        return ['success' => true];
    }

    /**
     * Handle successful payment intent
     */
    protected function handlePaymentIntentSucceeded($paymentIntent): void
    {
        $metadata = $paymentIntent->metadata;
        $appointmentId = $metadata->appointment_id ?? null;

        if (!$appointmentId) {
            return;
        }

        $appointment = Appointment::find($appointmentId);
        if (!$appointment) {
            return;
        }

        // Create or update payment record
        $payment = Payment::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'user_id' => $appointment->user_id,
                'service_amount' => $metadata->service_amount ?? 0,
                'tip_amount' => $metadata->tip_amount ?? 0,
                'total_amount' => $metadata->total_amount ?? 0,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'stripe_charge_id' => $paymentIntent->charges->data[0]->id ?? null,
                'transaction_id' => $paymentIntent->id,
                'status' => 'completed',
                'payment_method' => 'stripe',
                'metadata' => (array) $metadata,
                'paid_at' => now(),
            ]
        );

        // Update appointment
        $appointment->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        // Create wallet entry
        $this->walletService->createWalletEntry($payment);

        // Dispatch jobs
        \App\Jobs\SendReviewRequestJob::dispatch($appointment)->delay(now()->addMinutes(5));
        
        // Send notifications
        $appointment->customer->notify(new \App\Notifications\PaymentReceivedNotification(
            $appointment,
            $metadata->service_amount ?? 0,
            $metadata->tip_amount ?? 0
        ));
    }

    /**
     * Handle failed payment intent
     */
    protected function handlePaymentIntentFailed($paymentIntent): void
    {
        $metadata = $paymentIntent->metadata;
        $appointmentId = $metadata->appointment_id ?? null;

        if (!$appointmentId) {
            return;
        }

        $appointment = Appointment::find($appointmentId);
        if (!$appointment) {
            return;
        }

        // Update or create payment record with failed status
        Payment::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'user_id' => $appointment->user_id,
                'service_amount' => $metadata->service_amount ?? 0,
                'tip_amount' => $metadata->tip_amount ?? 0,
                'total_amount' => $metadata->total_amount ?? 0,
                'stripe_payment_intent_id' => $paymentIntent->id,
                'status' => 'failed',
                'payment_method' => 'stripe',
                'metadata' => (array) $metadata,
            ]
        );
    }

    /**
     * Confirm payment manually (for testing or alternative flows)
     */
    public function confirmPayment(Appointment $appointment, float $tipAmount = 0, string $paymentIntentId = null): Payment
    {
        $serviceAmount = $appointment->service->price;
        $totalAmount = $serviceAmount + $tipAmount;

        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'service_amount' => $serviceAmount,
            'tip_amount' => $tipAmount,
            'total_amount' => $totalAmount,
            'stripe_payment_intent_id' => $paymentIntentId,
            'transaction_id' => $paymentIntentId ?? 'manual_' . time(),
            'status' => 'completed',
            'payment_method' => 'stripe',
            'paid_at' => now(),
        ]);

        $appointment->update([
            'payment_status' => 'paid',
            'paid_at' => now(),
        ]);

        // Create wallet entry
        $walletEntry = $this->walletService->createWalletEntry($payment);

        // Send notifications
        $appointment->customer->notify(new \App\Notifications\PaymentReceivedNotification(
            $appointment,
            $serviceAmount,
            $tipAmount
        ));

        $appointment->provider->user->notify(new \App\Notifications\EarningsNotification($walletEntry));

        // Dispatch review request job
        \App\Jobs\SendReviewRequestJob::dispatch($appointment)->delay(now()->addMinutes(5));

        return $payment;
    }
}
