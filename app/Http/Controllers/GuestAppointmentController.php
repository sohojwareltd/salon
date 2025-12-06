<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Provider;
use App\Models\Service;
use App\Models\User;
use App\Models\Role;
use App\Notifications\GuestAppointmentCredentials;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GuestAppointmentController extends Controller
{
    /**
     * Show guest appointment booking form
     */
    public function show(Provider $provider, Request $request)
    {
        $service = null;
        if ($request->has('service')) {
            $service = Service::find($request->service);
        }

        return view('pages.appointments.guest-book', compact('provider', 'service'));
    }

    /**
     * Store guest appointment
     */
    public function store(Request $request, Provider $provider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after:today',
            'start_time' => 'required',
            'notes' => 'nullable|string|max:500',
            'payment_method' => 'required|in:stripe,paypal',
        ]);

        DB::beginTransaction();
        try {
            // Check if user already exists
            $user = User::where('email', $validated['email'])->first();
            $isNewUser = false;
            $temporaryPassword = null;

            if (!$user) {
                // Create new user account
                $isNewUser = true;
                $temporaryPassword = Str::random(10);
                
                // Get customer role
                $customerRole = Role::where('name', 'customer')->first();

                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                    'password' => Hash::make($temporaryPassword),
                    'role_id' => $customerRole ? $customerRole->id : 3,
                    'email_verified_at' => now(), // Auto verify guest users
                ]);
            }

            // Get service details
            $service = Service::findOrFail($validated['service_id']);
            
            // Calculate end time based on service duration
            $startTime = \Carbon\Carbon::parse($validated['start_time']);
            $endTime = $startTime->copy()->addMinutes($service->duration);

            // Create appointment
            $appointment = Appointment::create([
                'user_id' => $user->id,
                'provider_id' => $provider->id,
                'service_id' => $service->id,
                'appointment_date' => $validated['appointment_date'],
                'start_time' => $startTime->format('H:i:s'),
                'end_time' => $endTime->format('H:i:s'),
                'total_amount' => $service->price,
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            // Send email notification with credentials if new user
            if ($isNewUser) {
                $user->notify(new GuestAppointmentCredentials($appointment, $temporaryPassword));
            }

            // Redirect to payment based on payment method
            if ($validated['payment_method'] === 'stripe') {
                return redirect()->route('guest.payment.stripe', $appointment);
            } else {
                return redirect()->route('guest.payment.paypal', $appointment);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Failed to book appointment: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Process Stripe payment for guest
     */
    public function stripePayment(Appointment $appointment)
    {
        if ($appointment->payment_status === 'paid') {
            return redirect()->route('appointments.thank-you', $appointment);
        }

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => $appointment->service->name,
                            'description' => 'Appointment with ' . $appointment->provider->name,
                        ],
                        'unit_amount' => $appointment->total_amount * 100, // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('guest.payment.success', $appointment),
                'cancel_url' => route('guest.payment.cancel', $appointment),
                'customer_email' => $appointment->user->email,
                'metadata' => [
                    'appointment_id' => $appointment->id,
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Payment initialization failed: ' . $e->getMessage()]);
        }
    }

    /**
     * Process PayPal payment for guest
     */
    public function paypalPayment(Appointment $appointment)
    {
        if ($appointment->payment_status === 'paid') {
            return redirect()->route('appointments.thank-you', $appointment);
        }

        // PayPal implementation (similar to existing PaymentController)
        return view('pages.appointments.guest-paypal', compact('appointment'));
    }

    /**
     * Payment success callback
     */
    public function paymentSuccess(Appointment $appointment)
    {
        // Update payment status
        $appointment->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
        ]);

        // Create payment record
        \App\Models\Payment::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->user_id,
            'amount' => $appointment->total_amount,
            'service_amount' => $appointment->total_amount,
            'tip_amount' => 0,
            'total_amount' => $appointment->total_amount,
            'payment_method' => 'stripe',
            'status' => 'completed',
            'transaction_id' => 'GUEST_' . time(),
        ]);

        return redirect()->route('appointments.thank-you', $appointment)
            ->with('success', 'Payment successful! Your appointment is confirmed.');
    }

    /**
     * Payment cancel callback
     */
    public function paymentCancel(Appointment $appointment)
    {
        return redirect()->route('guest.appointments.book', $appointment->provider)
            ->with('error', 'Payment was cancelled. Please try again.');
    }
}
