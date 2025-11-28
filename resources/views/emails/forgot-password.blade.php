@extends('emails.layout')

@section('content')
<div class="greeting">
    Password Reset Request 🔐
</div>

<div class="content">
    <p>Hi <strong>{{ $userName }}</strong>,</p>
    <p>We received a request to reset the password for your account. If you made this request, click the button below to create a new password:</p>
</div>

<a href="{{ $resetUrl }}" class="btn" style="margin: 30px auto; display: inline-block;">
    🔑 Reset Password
</a>

<div class="alert alert-warning">
    ⏰ <strong>Security Notice:</strong><br>
    This password reset link will expire in <strong>60 minutes</strong>. After that, you'll need to request a new one.
</div>

<div class="info-box">
    <div class="info-box-title">📋 Request Details</div>
    
    <div class="info-row">
        <span class="info-label">Account Email</span>
        <span class="info-value">{{ $userEmail }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">Request Time</span>
        <span class="info-value">{{ now()->format('M d, Y h:i A') }}</span>
    </div>
    
    <div class="info-row">
        <span class="info-label">IP Address</span>
        <span class="info-value">{{ request()->ip() ?? 'N/A' }}</span>
    </div>
</div>

<div class="divider"></div>

<div class="content">
    <p style="font-size: 16px; font-weight: 600; color: #0f172a; margin-bottom: 10px;">
        🛡️ Security Tips
    </p>
    <p style="font-size: 14px; color: #64748b; line-height: 1.6;">
        • Choose a strong password with at least 8 characters<br>
        • Use a combination of letters, numbers, and symbols<br>
        • Don't reuse passwords from other accounts<br>
        • Never share your password with anyone
    </p>
</div>

<div class="alert alert-error" style="margin-top: 20px;">
    ⚠️ <strong>Didn't Request This?</strong><br>
    If you didn't request a password reset, please ignore this email. Your password will remain unchanged and your account is secure.
    <br><br>
    However, if you're concerned about unauthorized access, we recommend:
    <br>
    • Changing your password immediately<br>
    • Reviewing your recent account activity<br>
    • Contacting our support team
</div>

<div class="divider"></div>

<div class="content" style="background: #f8fafc; padding: 20px; border-radius: 8px; margin-top: 20px;">
    <p style="font-size: 12px; color: #64748b; margin: 0;">
        <strong>Trouble clicking the button?</strong> Copy and paste this URL into your browser:<br>
        <span style="color: #872341; word-break: break-all;">{{ $resetUrl }}</span>
    </p>
</div>

<div class="divider"></div>

<div class="content">
    <p style="font-size: 14px; color: #64748b; text-align: center;">
        Need help? Contact our support team at <a href="mailto:support@salon.com" style="color: #872341; text-decoration: none;">support@salon.com</a>
    </p>
</div>
@endsection
