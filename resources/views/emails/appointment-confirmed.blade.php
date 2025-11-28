@extends('emails.layout')

@section('content')
<div class="greeting">
    Great News, {{ $userName }}! 🎉
</div>

<div class="content">
    @if($userRole === 'customer')
        <p>Your appointment has been <strong style="color: #10b981;">confirmed</strong>! We're looking forward to serving you.</p>
        <p><strong style="color: #872341;">Payment Required:</strong> Please complete your payment to secure your booking.</p>
    @elseif($userRole === 'provider')
        <p>You have successfully confirmed the appointment with <strong>{{ $appointment->customer->name }}</strong>.</p>
        <p>The customer will be notified to complete the payment.</p>
    @else
        <p>Appointment <strong>#{{ $appointment->id }}</strong> has been confirmed by the provider.</p>
    @endif
</div>

<div class="info-box">
    <div class="info-box-title">✅ Confirmed Appointment Details</div>
    
    <div class="info-row">
        <span class="info-label">Booking ID</span>
        <span class="info-value">#{{ $appointment->id }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value" style="color: #10b981;">● Confirmed</span>
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
    
    <div class="info-row">
        <span class="info-label">Payment Status</span>
        <span class="info-value" style="color: #f59e0b;">⏳ Pending</span>
    </div>
</div>

@if($userRole === 'customer')
    <div class="alert">
        💳 <strong>Payment Required:</strong><br>
        Please complete your payment within 24 hours to secure your booking. Unpaid appointments may be cancelled.
    </div>
    
    <a href="{{ route('customer.payment.show', $appointment->id) }}" class="btn">
        Pay Now
    </a>
    
    <a href="{{ route('customer.booking.details', $appointment->id) }}" class="btn btn-secondary">
        View Details
    </a>
@elseif($userRole === 'provider')
    <div class="alert alert-success">
        ✓ <strong>Action Completed:</strong><br>
        The customer has been notified to complete the payment. You'll receive another notification once payment is received.
    </div>
    
    <a href="{{ route('provider.booking.details', $appointment->id) }}" class="btn">
        View Appointment
    </a>
@else
    <a href="{{ url('/admin') }}" class="btn">
        View in Admin Panel
    </a>
@endif

<div class="divider"></div>

<div class="content">
    <p style="font-size: 13px; color: #64748b;">
        <strong>Cancellation Policy:</strong> If you need to reschedule or cancel, please do so at least 24 hours in advance.
    </p>
</div>
@endsection
