<?php

use Illuminate\Support\Facades\Route;
use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentBooked;
use App\Mail\AppointmentConfirmed;
use App\Mail\PaymentCompleted;
use App\Mail\ServiceCompleted;
use App\Mail\VerifyEmailMail;
use App\Mail\ForgotPasswordMail;

/*
|--------------------------------------------------------------------------
| Test Email Routes
|--------------------------------------------------------------------------
| These routes are for testing email templates in development
|
*/

Route::get('/test-emails', function () {
    return view('test-emails');
});

Route::get('/test-email/appointment-booked', function () {
    $appointment = Appointment::with(['customer', 'provider.user', 'services'])->first();
    if (!$appointment) {
        return 'No appointments found. Please seed the database first.';
    }
    
    $customer = $appointment->customer;
    
    Mail::to($customer->email)->send(new AppointmentBooked(
        $customer,
        'customer',
        $appointment
    ));
    
    return 'Appointment Booked email sent to ' . $customer->email . '. Check your Mailtrap inbox!';
});

Route::get('/test-email/appointment-confirmed', function () {
    $appointment = Appointment::with(['customer', 'provider.user', 'services'])->first();
    if (!$appointment) {
        return 'No appointments found. Please seed the database first.';
    }
    
    $customer = $appointment->customer;
    
    Mail::to($customer->email)->send(new AppointmentConfirmed(
        $customer,
        'customer',
        $appointment
    ));
    
    return 'Appointment Confirmed email sent to ' . $customer->email . '. Check your Mailtrap inbox!';
});

Route::get('/test-email/payment-completed', function () {
    $appointment = Appointment::with(['customer', 'provider.user', 'services', 'payments'])->first();
    if (!$appointment) {
        return 'No appointments found. Please seed the database first.';
    }
    
    // Create a fake payment if none exists
    $payment = $appointment->payments()->first();
    if (!$payment) {
        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'user_id' => $appointment->customer_id,
            'provider_id' => $appointment->provider_id,
            'amount' => $appointment->total_amount,
            'service_amount' => $appointment->total_amount,
            'tip_amount' => 10,
            'total_amount' => $appointment->total_amount + 10,
            'commission_amount' => ($appointment->total_amount * $appointment->provider->commission_percentage) / 100,
            'provider_earning' => $appointment->total_amount - (($appointment->total_amount * $appointment->provider->commission_percentage) / 100) + 10,
            'payment_method' => 'stripe',
            'transaction_id' => 'test_' . uniqid(),
            'status' => 'completed',
            'paid_at' => now(),
        ]);
    }
    
    $customer = $appointment->customer;
    
    Mail::to($customer->email)->send(new PaymentCompleted(
        $customer,
        'customer',
        $payment,
        $appointment
    ));
    
    return 'Payment Completed email sent to ' . $customer->email . '. Check your Mailtrap inbox!';
});

Route::get('/test-email/service-completed', function () {
    $appointment = Appointment::with(['customer', 'provider.user', 'services'])->first();
    if (!$appointment) {
        return 'No appointments found. Please seed the database first.';
    }
    
    $customer = $appointment->customer;
    
    Mail::to($customer->email)->send(new ServiceCompleted(
        $customer,
        'customer',
        $appointment
    ));
    
    return 'Service Completed email sent to ' . $customer->email . '. Check your Mailtrap inbox!';
});

Route::get('/test-email/verify-email', function () {
    $user = User::role('customer')->first();
    if (!$user) {
        return 'No customer users found. Please seed the database first.';
    }
    
    $verificationUrl = url('/verify-email/' . base64_encode($user->email) . '?expires=' . (time() + 3600));
    
    Mail::to($user->email)->send(new VerifyEmailMail(
        $user,
        $verificationUrl,
        'customer'
    ));
    
    return 'Verify Email sent to ' . $user->email . '. Check your Mailtrap inbox!';
});

Route::get('/test-email/forgot-password', function () {
    $user = User::role('customer')->first();
    if (!$user) {
        return 'No customer users found. Please seed the database first.';
    }
    
    $resetUrl = url('/reset-password/' . base64_encode($user->email) . '?token=' . uniqid());
    
    Mail::to($user->email)->send(new ForgotPasswordMail(
        $user,
        $resetUrl
    ));
    
    return 'Forgot Password email sent to ' . $user->email . '. Check your Mailtrap inbox!';
});
