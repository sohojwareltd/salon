<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Account Credentials</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #872341, #BE3144);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .email-header p {
            font-size: 16px;
            opacity: 0.95;
        }
        .email-body {
            padding: 40px 30px;
        }
        .welcome-message {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(5, 150, 105, 0.1));
            border-left: 4px solid #10b981;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .welcome-message h2 {
            color: #065f46;
            font-size: 20px;
            margin-bottom: 8px;
        }
        .welcome-message p {
            color: #047857;
            font-size: 15px;
        }
        .credentials-box {
            background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(37, 99, 235, 0.05));
            border: 2px solid rgba(59, 130, 246, 0.3);
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }
        .credentials-box h3 {
            color: #1e40af;
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .credential-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .credential-item:last-child {
            margin-bottom: 0;
        }
        .credential-label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        .credential-value {
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            font-family: 'Courier New', monospace;
        }
        .appointment-details {
            background: #f9fafb;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }
        .appointment-details h3 {
            color: #872341;
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }
        .detail-value {
            font-size: 14px;
            color: #111827;
            font-weight: 600;
            text-align: right;
        }
        .services-list {
            margin-top: 8px;
        }
        .service-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 12px;
            background: white;
            border-radius: 6px;
            margin-bottom: 6px;
        }
        .service-item:last-child {
            margin-bottom: 0;
        }
        .service-name {
            font-size: 14px;
            color: #374151;
        }
        .service-price {
            font-size: 14px;
            color: #10b981;
            font-weight: 600;
        }
        .btn-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #872341, #BE3144);
            color: white;
            padding: 16px 40px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
            transition: all 0.3s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(135, 35, 65, 0.4);
        }
        .security-notice {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin: 30px 0;
        }
        .security-notice h4 {
            color: #92400e;
            font-size: 16px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .security-notice p {
            color: #78350f;
            font-size: 14px;
        }
        .email-footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            color: #6b7280;
            font-size: 13px;
            margin-bottom: 8px;
        }
        .social-links {
            margin-top: 20px;
        }
        .social-links a {
            display: inline-block;
            width: 36px;
            height: 36px;
            line-height: 36px;
            background: #872341;
            color: white;
            border-radius: 50%;
            margin: 0 5px;
            text-decoration: none;
            font-size: 16px;
        }
        .total-amount {
            background: linear-gradient(135deg, #065f46, #047857);
            color: white;
            padding: 16px;
            border-radius: 8px;
            margin-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-amount .label {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .total-amount .value {
            font-size: 24px;
            font-weight: 800;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>🎉 Welcome to {{ config('app.name') }}!</h1>
            <p>Your account has been successfully created</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <!-- Welcome Message -->
            <div class="welcome-message">
                <h2>Hello {{ $user->name }}! 👋</h2>
                <p>Thank you for booking with us! We've created an account for you to manage your appointments easily.</p>
            </div>

            <!-- Login Credentials -->
            <div class="credentials-box">
                <h3>🔑 Your Login Credentials</h3>
                <div class="credential-item">
                    <div>
                        <div class="credential-label">Email Address</div>
                        <div class="credential-value">{{ $user->email }}</div>
                    </div>
                </div>
                <div class="credential-item">
                    <div>
                        <div class="credential-label">Temporary Password</div>
                        <div class="credential-value">{{ $password }}</div>
                    </div>
                </div>
            </div>

            <!-- Login Button -->
            <div class="btn-container">
                <a href="{{ route('login') }}" class="btn-primary">
                    🔐 Login to Your Account
                </a>
            </div>

            <!-- Appointment Details -->
            <div class="appointment-details">
                <h3>📅 Your Appointment Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Provider</span>
                    <span class="detail-value">{{ $appointment->provider->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Time</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value" style="color: #f59e0b;">Pending Confirmation</span>
                </div>

                <!-- Services -->
                <div style="margin-top: 20px;">
                    <div class="detail-label" style="margin-bottom: 12px;">Services ({{ $appointment->services->count() }})</div>
                    <div class="services-list">
                        @foreach($appointment->services as $service)
                        <div class="service-item">
                            <span class="service-name">{{ $service->name }}</span>
                            <span class="service-price">{{ App\Facades\Settings::formatPrice($service->price, false) }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="total-amount">
                    <span class="label">Total Amount</span>
                    <span class="value">{{ App\Facades\Settings::formatPrice($appointment->total_amount, false) }}</span>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="security-notice">
                <h4>🔒 Important Security Notice</h4>
                <p>For your security, please change your password after your first login. You can do this from your account settings.</p>
            </div>

            <!-- Additional Info -->
            <p style="color: #6b7280; font-size: 14px; line-height: 1.8; margin-top: 25px;">
                Your appointment is currently <strong style="color: #f59e0b;">pending confirmation</strong>. 
                The provider will review and confirm it shortly. You'll receive a notification once it's confirmed.
            </p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p style="font-weight: 600; color: #872341; font-size: 16px; margin-bottom: 15px;">
                {{ config('app.name') }}
            </p>
            <p>Professional beauty and grooming services</p>
            <p>Need help? Contact us at {{ config('mail.from.address') }}</p>
            
            <div class="social-links">
                <a href="#">f</a>
                <a href="#">t</a>
                <a href="#">i</a>
            </div>
            
            <p style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
