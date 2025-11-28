<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Stripe API key
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Show payment page for an appointment
     */
    public function show(Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated customer
        if ($appointment->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this appointment.');
        }

        // Check if appointment is confirmed
        if ($appointment->status !== 'confirmed') {
            return redirect()->route('customer.bookings')
                ->with('error', 'This appointment is not confirmed yet.');
        }

        // Check if already paid
        $existingPayment = Payment::where('appointment_id', $appointment->id)
            ->where('status', 'completed')
            ->first();

        if ($existingPayment) {
            return redirect()->route('customer.bookings')
                ->with('info', 'This appointment has already been paid.');
        }

        // Load appointment relationships
        $appointment->load(['provider', 'services']);

        return view('customer.payment', compact('appointment'));
    }

    /**
     * Create Stripe Checkout Session
     */
    public function createCheckoutSession(Request $request, Appointment $appointment)
    {
        // Ensure the appointment belongs to the authenticated customer
        if ($appointment->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        // Check if appointment is confirmed
        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'This appointment is not confirmed yet.');
        }

        // Load appointment relationships
        $appointment->load(['services']);

        try {
            // Get tip amount from request (default 0)
            $tipAmount = $request->input('tip_amount', 0);
            $serviceAmount = $appointment->total_amount;
            $totalAmount = $serviceAmount + $tipAmount;
            
            // Calculate amount in cents (Stripe uses smallest currency unit)
            $amountInCents = (int) ($totalAmount * 100);

            // Create or get pending payment record
            $payment = Payment::firstOrCreate(
                [
                    'appointment_id' => $appointment->id,
                    'status' => 'pending'
                ],
                [
                    'user_id' => Auth::id(),
                    'service_amount' => $serviceAmount,
                    'tip_amount' => $tipAmount,
                    'total_amount' => $totalAmount,
                    'amount' => $totalAmount,
                    'currency' => 'usd',
                    'metadata' => json_encode([
                        'appointment_id' => $appointment->id,
                        'customer_id' => Auth::id(),
                        'tip_amount' => $tipAmount,
                    ])
                ]
            );

            // Create Stripe Checkout Session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $amountInCents,
                        'product_data' => [
                            'name' => 'Appointment Payment',
                            'description' => 'Services: ' . $appointment->services->pluck('service_name')->join(', '),
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('customer.payment.success', ['appointment' => $appointment->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('customer.payment.cancel', ['appointment' => $appointment->id]),
                'client_reference_id' => $appointment->id,
                'metadata' => [
                    'appointment_id' => $appointment->id,
                    'payment_id' => $payment->id,
                ],
            ]);

            // Store checkout session ID
            $payment->update([
                'stripe_checkout_session_id' => $session->id
            ]);

            // Return checkout URL
            return response()->json([
                'url' => $session->url
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request, Appointment $appointment)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('customer.bookings')
                ->with('error', 'Invalid payment session.');
        }

        try {
            // Retrieve the session from Stripe
            $session = StripeSession::retrieve($sessionId);

            // Find the payment record
            $payment = Payment::where('stripe_checkout_session_id', $sessionId)->first();

            if ($payment && $session->payment_status === 'paid') {
                // Update payment record
                $payment->update([
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);

                // Update appointment status to paid
                $appointment->update([
                    'payment_status' => 'paid'
                ]);

                // Create wallet entry with commission and tips
                $provider = $appointment->provider;
                $serviceAmount = $payment->service_amount;
                $tipAmount = $payment->tip_amount;
                
                // Calculate provider commission from service amount
                $providerCommissionRate = $provider->commission_percentage / 100;
                $providerCommission = $serviceAmount * $providerCommissionRate;
                $salonAmount = $serviceAmount - $providerCommission;
                
                // Provider gets commission + full tip amount
                $totalProviderAmount = $providerCommission + $tipAmount;
                
                // Check if wallet entry doesn't already exist
                $walletEntry = \App\Models\ProviderWalletEntry::where('appointment_id', $appointment->id)
                    ->where('payment_id', $payment->id)
                    ->first();
                
                if (!$walletEntry) {
                    \App\Models\ProviderWalletEntry::create([
                        'provider_id' => $provider->id,
                        'appointment_id' => $appointment->id,
                        'payment_id' => $payment->id,
                        'service_amount' => $serviceAmount,
                        'provider_amount' => $providerCommission,
                        'tips_amount' => $tipAmount,
                        'total_provider_amount' => $totalProviderAmount,
                        'type' => 'earning',
                    ]);
                    
                    // Update provider wallet balance
                    $provider->wallet_balance += $totalProviderAmount;
                    $provider->save();
                }

                // Send payment notifications
                $appointment->load(['customer', 'provider.user']);
                $customerName = $appointment->customer->name;
                $amount = number_format($payment->total_amount, 2);
                
                // Notify Customer
                makeNotification(
                    $appointment->customer_id,
                    'Payment Successful',
                    "Your payment of \${$amount} for the appointment was successful.",
                    route('customer.booking.details', $appointment->id),
                    'payment'
                );
                
                // Notify Provider
                makeNotification(
                    $appointment->provider->user_id,
                    'Payment Received',
                    "Payment of \${$amount} received from {$customerName}. Your earnings have been added to your wallet.",
                    route('provider.wallet.index'),
                    'payment'
                );

                return view('customer.payment-success', compact('appointment', 'payment'));
            }

            return redirect()->route('customer.bookings')
                ->with('error', 'Payment verification failed.');

        } catch (\Exception $e) {
            return redirect()->route('customer.bookings')
                ->with('error', 'Payment verification error: ' . $e->getMessage());
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Appointment $appointment)
    {
        return view('customer.payment-cancel', compact('appointment'));
    }
}
