<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a payment intent for an appointment
     */
    public function createPaymentIntent(Appointment $appointment, float $tipAmount = 0)
    {
        $serviceAmount = $appointment->service->price;
        $totalAmount = $serviceAmount + $tipAmount;

        // Create Stripe Payment Intent
        $intent = PaymentIntent::create([
            'amount' => $totalAmount * 100, // Convert to cents
            'currency' => 'usd',
            'metadata' => [
                'appointment_id' => $appointment->id,
                'user_id' => $appointment->user_id,
            ],
        ]);

        // Create payment record
        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'service_amount' => $serviceAmount,
            'tip_amount' => $tipAmount,
            'total_amount' => $totalAmount,
            'stripe_payment_intent_id' => $intent->id,
            'status' => 'pending',
            'payment_method' => 'card',
        ]);

        return [
            'payment' => $payment,
            'client_secret' => $intent->client_secret,
        ];
    }

    /**
     * Confirm payment after successful charge
     */
    public function confirmPayment(Payment $payment, string $chargeId)
    {
        $payment->update([
            'stripe_charge_id' => $chargeId,
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        // Update appointment payment status
        $payment->appointment->update([
            'payment_status' => 'paid',
        ]);

        return $payment;
    }
}
