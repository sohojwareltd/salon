@extends('emails.layout')

@section('content')
<div class="greeting">
    Hello {{ $userName }}! 👋
</div>

<div class="content">
    <p>Great news! Your appointment has been successfully booked.</p>
    
    @if($userRole === 'customer')
        <p>We've received your booking request and it's currently <strong style="color: #f59e0b;">pending confirmation</strong> from the provider. You'll receive another email once it's confirmed.</p>
    @elseif($userRole === 'provider')
        <p>You have a new appointment request from <strong>{{ $appointment->customer->name }}</strong>. Please review and confirm the booking as soon as possible.</p>
    @else
        <p>A new appointment has been created in the system. Booking ID: <strong>#{{ $appointment->id }}</strong></p>
    @endif
</div>

<div class="info-box">
    <div class="info-box-title">📅 Appointment Details</div>
    
    <div class="info-row">
        <span class="info-label">Booking ID</span>
        <span class="info-value">#{{ $appointment->id }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Customer</span>
        <span class="info-value">{{ $appointment->customer->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Provider</span>
        <span class="info-value">{{ $appointment->provider->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Date</span>
        <span class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Time</span>
        <span class="info-value">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Services</span>
        <span class="info-value">{{ $appointment->services->pluck('service_name')->join(', ') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Total Amount</span>
        <span class="info-value" style="color: #10b981; font-size: 18px;">${{ number_format($appointment->total_amount, 2) }}</span>
    </div>
</div>

@if($userRole === 'customer')
    <div class="alert alert-info">
        ℹ️ <strong>What's Next?</strong><br>
        The provider will review your request and confirm the appointment. You'll receive a confirmation email with payment instructions.
    </div>
    
    <a href="{{ route('customer.booking.details', $appointment->id) }}" class="btn">
        View Booking Details
    </a>
@elseif($userRole === 'provider')
    <div class="alert alert-info">
        ⚡ <strong>Action Required:</strong><br>
        Please log in to your dashboard and confirm or decline this appointment request.
    </div>
    
    <a href="{{ route('provider.booking.details', $appointment->id) }}" class="btn">
        Review Appointment
    </a>
@else
    <a href="{{ url('/admin') }}" class="btn">
        View in Admin Panel
    </a>
@endif

<div class="divider"></div>

<div class="content">
    <p style="font-size: 14px; color: #64748b;">
        If you have any questions, feel free to contact our support team.
    </p>
</div>
@endsection
