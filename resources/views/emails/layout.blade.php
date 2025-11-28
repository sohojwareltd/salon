<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Notification' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 40px 20px;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #872341, #BE3144);
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .email-header::before {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }
        .logo {
            font-size: 32px;
            font-weight: 800;
            color: white;
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }
        .header-subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            position: relative;
            z-index: 1;
        }
        .email-body {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
        }
        .content {
            font-size: 15px;
            color: #475569;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .content p {
            margin-bottom: 15px;
        }
        .info-box {
            background: #f8fafc;
            border-left: 4px solid #872341;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .info-box-title {
            font-size: 16px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }
        .info-value {
            font-size: 14px;
            color: #1e293b;
            font-weight: 600;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #872341, #BE3144);
            color: white;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 15px;
            text-align: center;
            margin: 20px 0;
            box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
        }
        .btn:hover {
            background: linear-gradient(135deg, #6d1d35, #a12839);
        }
        .btn-secondary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        .alert {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            font-size: 14px;
            color: #78350f;
        }
        .alert-success {
            background: #dcfce7;
            border-left-color: #10b981;
            color: #065f46;
        }
        .alert-info {
            background: #dbeafe;
            border-left-color: #3b82f6;
            color: #1e40af;
        }
        .footer {
            background: #f8fafc;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e2e8f0;
        }
        .footer-text {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 15px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 8px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
        }
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin: 30px 0;
        }
        @media only screen and (max-width: 600px) {
            .email-body {
                padding: 30px 20px;
            }
            .greeting {
                font-size: 20px;
            }
            .info-row {
                flex-direction: column;
                gap: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">💈 {{ config('app.name') }}</div>
            <div class="header-subtitle">Your Trusted Salon Booking Platform</div>
        </div>

        <!-- Body -->
        <div class="email-body">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-text">
                <strong>{{ config('app.name') }}</strong><br>
                Your satisfaction is our priority. We're here to provide you with the best salon experience.
            </div>
            
            <div class="divider"></div>
            
            <div class="footer-text">
                Questions? Contact us at <a href="mailto:support@salon.com" style="color: #872341; text-decoration: none;">support@salon.com</a>
            </div>
            
            <div class="social-links">
                <a href="#">Facebook</a> • 
                <a href="#">Instagram</a> • 
                <a href="#">Twitter</a>
            </div>
            
            <div class="footer-text" style="margin-top: 20px; font-size: 12px;">
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.<br>
                This email was sent to you because you have an account with us.
            </div>
        </div>
    </div>
</body>
</html>
