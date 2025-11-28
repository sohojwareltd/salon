@extends('emails.layout')

@section('content')
<div class="greeting">
    Payment Successful! ✅
</div>

<div class="content">
    @if($userRole === 'customer')
        <p>Thank you, <strong>{{ $userName }}</strong>! Your payment has been successfully processed.</p>
        <p>Your appointment is now fully confirmed and secured. We look forward to serving you!</p>
    @elseif($userRole === 'provider')
        <p>Great news! Payment of <strong>${{ number_format($payment->total_amount, 2) }}</strong> has been received for your upcoming appointment.</p>
        <p>Your earnings have been added to your wallet. The customer <strong>{{ $appointment->customer->name }}</strong> is all set for their appointment.</p>
    @else
        <p>Payment received for Appointment <strong>#{{ $appointment->id }}</strong>.</p>
    @endif
</div>

<div class="info-box">
    <div class="info-box-title">💳 Payment Details</div>
    
    <div class="info-row">
        <span class="info-label">Transaction ID</span>
        <span class="info-value">{{ $payment->transaction_id }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Payment Method</span>
        <span class="info-value">{{ ucfirst($payment->payment_method) }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Service Amount</span>
        <span class="info-value">${{ number_format($payment->service_amount ?? $appointment->total_amount, 2) }}</span>
    </div>
    
    @if($payment->tip_amount > 0)
    <div class="info-row">
        <span class="info-label">Tip</span>
        <span class="info-value" style="color: #f59e0b;">${{ number_format($payment->tip_amount, 2) }}</span>
    </div>
    @endif
    
    <div class="info-row">
        <span class="info-label">Total Paid</span>
        <span class="info-value" style="color: #10b981; font-size: 18px; font-weight: 800;">${{ number_format($payment->total_amount, 2) }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Payment Date</span>
        <span class="info-value">{{ $payment->paid_at->format('M d, Y h:i A') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value" style="color: #10b981;">✓ Completed</span>
    </div>
</div>

<div class="info-box" style="background: #fef3c7; border-left-color: #f59e0b;">
    <div class="info-box-title">📅 Appointment Summary</div>
    
    <div class="info-row">
        <span class="info-label">Booking ID</span>
        <span class="info-value">#{{ $appointment->id }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Provider</span>
        <span class="info-value">{{ $appointment->provider->name }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Date & Time</span>
        <span class="info-value">
            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} at 
            {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}
        </span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Services</span>
        <span class="info-value">{{ $appointment->services->pluck('service_name')->join(', ') }}</span>
    </div>
</div>

@if($userRole === 'customer')
    <div class="alert alert-success">
        🎉 <strong>All Set!</strong><br>
        Your appointment is confirmed and paid. Please arrive 5-10 minutes early. Don't forget to bring a valid ID.
    </div>
    
    <a href="{{ route('customer.booking.details', $appointment->id) }}" class="btn">
        View Appointment Details
    </a>
@elseif($userRole === 'provider')
    <div class="alert alert-success">
        💰 <strong>Earnings Added:</strong><br>
        Your earnings of ${{ number_format($payment->provider_earning, 2) }} have been added to your wallet.
    </div>
    
    <a href="{{ route('provider.wallet.index') }}" class="btn">
        View Wallet
    </a>
    
    <a href="{{ route('provider.booking.details', $appointment->id) }}" class="btn btn-secondary">
        View Appointment
    </a>
@else
    <a href="{{ url('/admin') }}" class="btn">
        View in Admin Panel
    </a>
@endif

<div class="divider"></div>

<div class="content">
    <p style="font-size: 14px; color: #64748b;">
        A receipt has been generated for your records. If you have any questions about this transaction, please contact our support team.
    </p>
</div>
@endsection
