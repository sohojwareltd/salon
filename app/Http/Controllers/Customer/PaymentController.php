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

                // Send payment email to customer
                \Illuminate\Support\Facades\Mail::to($appointment->customer->email)
                    ->send(new \App\Mail\PaymentCompleted(
                        $appointment->customer,
                        'customer',
                        $payment,
                        $appointment
                    ));

                // Send payment email to provider
                \Illuminate\Support\Facades\Mail::to($appointment->provider->user->email)
                    ->send(new \App\Mail\PaymentCompleted(
                        $appointment->provider->user,
                        'provider',
                        $payment,
                        $appointment
                    ));

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

    /**
     * Create PayPal order
     */
    public function createPayPalOrder(Request $request, Appointment $appointment)
    {
        try {
            // Ensure the appointment belongs to the authenticated customer
            if ($appointment->customer_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }

            $tipAmount = $request->input('tip_amount', 0);
            $totalAmount = $appointment->total_amount + $tipAmount;

            // Get PayPal credentials based on mode
            $mode = config('services.paypal.mode');
            $clientId = config("services.paypal.{$mode}.client_id");
            $clientSecret = config("services.paypal.{$mode}.client_secret");

            if (!$clientId || !$clientSecret) {
                return response()->json(['error' => 'PayPal credentials not configured'], 500);
            }

            // Get PayPal access token
            $baseUrl = $mode === 'sandbox' 
                ? 'https://api-m.sandbox.paypal.com' 
                : 'https://api-m.paypal.com';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/v1/oauth2/token");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
            curl_setopt($ch, CURLOPT_USERPWD, "{$clientId}:{$clientSecret}");
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json', 'Accept-Language: en_US']);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                return response()->json(['error' => 'Failed to get PayPal access token'], 500);
            }

            $accessToken = json_decode($result)->access_token;

            // Create PayPal order
            $orderData = [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => 'appointment_' . $appointment->id,
                    'description' => 'Appointment Payment - Booking #' . $appointment->id,
                    'amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($totalAmount, 2, '.', ''),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => 'USD',
                                'value' => number_format($appointment->total_amount, 2, '.', '')
                            ]
                        ]
                    ],
                    'items' => [[
                        'name' => 'Appointment Services',
                        'description' => $appointment->services->pluck('service_name')->join(', '),
                        'unit_amount' => [
                            'currency_code' => 'USD',
                            'value' => number_format($appointment->total_amount, 2, '.', '')
                        ],
                        'quantity' => '1'
                    ]]
                ]],
                'application_context' => [
                    'return_url' => route('payment.paypal.success') . '?appointment_id=' . $appointment->id . '&tip_amount=' . $tipAmount,
                    'cancel_url' => route('payment.paypal.cancel') . '?appointment_id=' . $appointment->id,
                    'brand_name' => config('app.name'),
                    'user_action' => 'PAY_NOW'
                ]
            ];

            // Add tip if present
            if ($tipAmount > 0) {
                $orderData['purchase_units'][0]['amount']['breakdown']['item_total']['value'] = number_format($totalAmount, 2, '.', '');
                $orderData['purchase_units'][0]['items'][] = [
                    'name' => 'Tip for Provider',
                    'unit_amount' => [
                        'currency_code' => 'USD',
                        'value' => number_format($tipAmount, 2, '.', '')
                    ],
                    'quantity' => '1'
                ];
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/v2/checkout/orders");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ]);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 201) {
                return response()->json(['error' => 'Failed to create PayPal order', 'details' => json_decode($result)], 500);
            }

            $order = json_decode($result);
            
            // Get approval URL
            $approvalUrl = collect($order->links)->firstWhere('rel', 'approve')->href ?? null;

            if (!$approvalUrl) {
                return response()->json(['error' => 'PayPal approval URL not found'], 500);
            }

            return response()->json(['url' => $approvalUrl]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle successful PayPal payment
     */
    public function paypalSuccess(Request $request)
    {
        try {
            $appointmentId = $request->query('appointment_id');
            $tipAmount = $request->query('tip_amount', 0);
            $token = $request->query('token');

            $appointment = Appointment::findOrFail($appointmentId);

            // Get PayPal credentials
            $mode = config('services.paypal.mode');
            $clientId = config("services.paypal.{$mode}.client_id");
            $clientSecret = config("services.paypal.{$mode}.client_secret");
            $baseUrl = $mode === 'sandbox' 
                ? 'https://api-m.sandbox.paypal.com' 
                : 'https://api-m.paypal.com';

            // Get access token
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/v1/oauth2/token");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
            curl_setopt($ch, CURLOPT_USERPWD, "{$clientId}:{$clientSecret}");
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
            
            $result = curl_exec($ch);
            curl_close($ch);
            $accessToken = json_decode($result)->access_token;

            // Capture payment
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "{$baseUrl}/v2/checkout/orders/{$token}/capture");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ]);
            
            $result = curl_exec($ch);
            curl_close($ch);
            $captureData = json_decode($result);

            if ($captureData->status === 'COMPLETED') {
                $totalAmount = $appointment->total_amount + $tipAmount;

                // Create payment record
                $payment = Payment::create([
                    'appointment_id' => $appointment->id,
                    'user_id' => $appointment->customer_id,
                    'provider_id' => $appointment->provider_id,
                    'amount' => $totalAmount,
                    'service_amount' => $appointment->total_amount,
                    'tip_amount' => $tipAmount,
                    'total_amount' => $totalAmount,
                    'commission_amount' => ($appointment->total_amount * $appointment->provider->commission_percentage) / 100,
                    'provider_earning' => $appointment->total_amount - (($appointment->total_amount * $appointment->provider->commission_percentage) / 100) + $tipAmount,
                    'payment_method' => 'paypal',
                    'transaction_id' => $captureData->id,
                    'status' => 'completed',
                    'paid_at' => now(),
                    'stripe_payment_intent_id' => null,
                ]);

                // Update appointment
                $appointment->update([
                    'payment_status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Update provider wallet
                $provider = $appointment->provider;
                $totalProviderAmount = $payment->provider_earning;
                
                if ($totalProviderAmount > 0) {
                    $provider->walletEntries()->create([
                        'appointment_id' => $appointment->id,
                        'amount' => $totalProviderAmount,
                        'balance_after' => $provider->wallet_balance + $totalProviderAmount,
                        'description' => "Payment received for Appointment #{$appointment->id}",
                        'total_provider_amount' => $totalProviderAmount,
                        'type' => 'earning',
                    ]);
                    
                    $provider->wallet_balance += $totalProviderAmount;
                    $provider->save();
                }

                // Send notifications
                $appointment->load(['customer', 'provider.user']);
                $amount = number_format($payment->total_amount, 2);
                
                makeNotification(
                    $appointment->customer_id,
                    'Payment Successful',
                    "Your PayPal payment of \${$amount} was successful.",
                    route('customer.booking.details', $appointment->id),
                    'payment'
                );
                
                makeNotification(
                    $appointment->provider->user_id,
                    'Payment Received',
                    "Payment of \${$amount} received via PayPal. Earnings added to your wallet.",
                    route('provider.wallet.index'),
                    'payment'
                );

                // Send payment email to customer
                \Illuminate\Support\Facades\Mail::to($appointment->customer->email)
                    ->send(new \App\Mail\PaymentCompleted(
                        $appointment->customer,
                        'customer',
                        $payment,
                        $appointment
                    ));

                // Send payment email to provider
                \Illuminate\Support\Facades\Mail::to($appointment->provider->user->email)
                    ->send(new \App\Mail\PaymentCompleted(
                        $appointment->provider->user,
                        'provider',
                        $payment,
                        $appointment
                    ));

                return view('customer.payment-success', compact('appointment', 'payment'));
            }

            return redirect()->route('customer.bookings')->with('error', 'Payment capture failed.');

        } catch (\Exception $e) {
            return redirect()->route('customer.bookings')->with('error', 'Payment error: ' . $e->getMessage());
        }
    }

    /**
     * Handle cancelled PayPal payment
     */
    public function paypalCancel(Request $request)
    {
        $appointmentId = $request->query('appointment_id');
        $appointment = Appointment::findOrFail($appointmentId);
        
        return view('customer.payment-cancel', compact('appointment'));
    }
}
