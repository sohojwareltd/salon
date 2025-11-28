@extends('emails.layout')

@section('content')
<div class="greeting">
    Verify Your Email Address 📧
</div>

<div class="content">
    <p>Hi <strong>{{ $userName }}</strong>,</p>
    <p>Welcome to our salon booking platform! We're excited to have you join our community.</p>
    <p>To get started and access all features, please verify your email address by clicking the button below:</p>
</div>

<a href="{{ $verificationUrl }}" class="btn" style="margin: 30px auto; display: inline-block;">
    ✓ Verify Email Address
</a>

<div class="alert alert-info">
    ⏰ <strong>Important:</strong><br>
    This verification link will expire in <strong>60 minutes</strong> for security reasons. If it expires, you can request a new verification email from your account settings.
</div>

<div class="divider"></div>

<div class="content">
    <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 10px;">
        What's Next?
    </p>
    
    @if($userRole === 'customer')
        <p style="font-size: 14px; color: #64748b; line-height: 1.6;">
            ✨ Browse our professional providers<br>
            📅 Book your first appointment<br>
            💳 Enjoy secure payment options<br>
            ⭐ Share your experience with reviews
        </p>
    @elseif($userRole === 'provider')
        <p style="font-size: 14px; color: #64748b; line-height: 1.6;">
            📋 Complete your profile with services and expertise<br>
            🕐 Set your availability and schedule<br>
            👥 Start receiving appointment bookings<br>
            💰 Track your earnings in the wallet
        </p>
    @endif
</div>

<div class="info-box" style="background: #fef3c7; border-left-color: #f59e0b;">
    <div class="info-box-title">📱 Your Account Details</div>
    
    <div class="info-row">
        <span class="info-label">Name</span>
        <span class="info-value">{{ $userName }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Email</span>
        <span class="info-value">{{ $userEmail }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Account Type</span>
        <span class="info-value">{{ ucfirst($userRole) }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Registration Date</span>
        <span class="info-value">{{ now()->format('M d, Y h:i A') }}</span>
    </div>
</div>

<div class="divider"></div>

<div class="content">
    <p style="font-size: 14px; color: #64748b;">
        <strong>Didn't create an account?</strong><br>
        If you didn't sign up for this account, please ignore this email or contact our support team if you have concerns.
    </p>
</div>

<div class="content" style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-top: 20px;">
    <p style="font-size: 12px; color: #64748b; margin: 0;">
        <strong>Trouble clicking the button?</strong> Copy and paste this URL into your browser:<br>
        <span style="color: #872341; word-break: break-all;">{{ $verificationUrl }}</span>
    </p>
</div>
@endsection
