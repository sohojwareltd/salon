@extends('emails.layout')

@section('content')
<div class="greeting">
    Service Completed! 🎉
</div>

<div class="content">
    @if($userRole === 'customer')
        <p>Hi <strong>{{ $userName }}</strong>,</p>
        <p>We hope you enjoyed your service! Your appointment with <strong>{{ $appointment->provider->name }}</strong> has been successfully completed.</p>
        <p>Thank you for choosing us. We'd love to hear about your experience!</p>
    @elseif($userRole === 'provider')
        <p>Congratulations, <strong>{{ $userName }}</strong>!</p>
        <p>Your appointment with <strong>{{ $appointment->customer->name }}</strong> has been marked as completed.</p>
        <p>Keep up the excellent work! We hope the customer was satisfied with your service.</p>
    @else
        <p>Appointment <strong>#{{ $appointment->id }}</strong> has been marked as completed.</p>
    @endif
</div>

<div class="info-box">
    <div class="info-box-title">✅ Completed Appointment</div>
    
    <div class="info-row">
        <span class="info-label">Booking ID</span>
        <span class="info-value">#{{ $appointment->id }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Provider</span>
        <span class="info-value">{{ $appointment->provider->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Customer</span>
        <span class="info-value">{{ $appointment->customer->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Services Provided</span>
        <span class="info-value">{{ $appointment->services->pluck('service_name')->join(', ') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Date & Time</span>
        <span class="info-value">
            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} at 
            {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
        </span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Duration</span>
        <span class="info-value">{{ $appointment->total_duration ?? 60 }} minutes</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Completion Date</span>
        <span class="info-value">{{ now()->format('M d, Y h:i A') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value" style="color: #10b981;">✓ Completed</span>
    </div>
</div>

@if($userRole === 'customer')
    <div class="alert alert-info">
        ⭐ <strong>Share Your Experience!</strong><br>
        Your feedback helps us improve and helps others make informed decisions. Please take a moment to rate your experience.
    </div>
    
    <a href="{{ route('customer.review', $appointment->id) }}" class="btn">
        ⭐ Write a Review
    </a>
    
    <p style="text-align: center; margin-top: 20px; font-size: 14px; color: #64748b;">
        We value your honest feedback! Your review will be visible to other customers.
    </p>
    
    <div class="divider"></div>
    
    <div class="content">
        <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 10px;">
            💇 Book Your Next Appointment
        </p>
        <p style="font-size: 14px; color: #64748b;">
            Looking fabulous? Keep it up! Book your next appointment and enjoy our professional services again.
        </p>
        <a href="{{ route('appointments.book') }}" class="btn btn-secondary" style="margin-top: 15px;">
            Book Again
        </a>
    </div>
    
@elseif($userRole === 'provider')
    <div class="alert alert-success">
        💰 <strong>Earnings Status:</strong><br>
        @if($appointment->payments()->where('payment_status', 'completed')->exists())
            Your earnings for this appointment have been credited to your wallet.
        @else
            Earnings will be credited once payment is completed.
        @endif
    </div>
    
    <a href="{{ route('provider.bookings.index') }}" class="btn">
        View All Appointments
    </a>
    
    <a href="{{ route('provider.wallet.index') }}" class="btn btn-secondary">
        Check Wallet
    </a>
    
    <div class="divider"></div>
    
    <div class="content">
        <p style="font-size: 14px; color: #64748b;">
            Great job! Keep delivering excellent service to build your reputation and grow your business.
        </p>
    </div>
    
@else
    <a href="{{ url('/admin') }}" class="btn">
        View in Admin Panel
    </a>
@endif

<div class="divider"></div>

<div class="content">
    <p style="font-size: 14px; color: #64748b; text-align: center;">
        Thank you for being part of our community! 🙏
    </p>
</div>
@endsection
