<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template Testing</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        h1 {
            color: white;
            text-align: center;
            margin-bottom: 40px;
            font-size: 36px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        .email-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }

        .email-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            text-decoration: none;
            display: block;
        }

        .email-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
        }

        .email-icon {
            font-size: 48px;
            margin-bottom: 15px;
            display: block;
        }

        .email-card h2 {
            color: #2d3748;
            margin-bottom: 10px;
            font-size: 20px;
        }

        .email-card p {
            color: #718096;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .test-btn {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        .test-btn:hover {
            opacity: 0.9;
        }

        .info-box {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }

        .info-box h3 {
            color: #2d3748;
            margin-bottom: 15px;
            font-size: 20px;
        }

        .info-box p {
            color: #4a5568;
            line-height: 1.8;
            margin-bottom: 10px;
        }

        .info-box strong {
            color: #2d3748;
        }

        .info-box code {
            background: #f7fafc;
            padding: 2px 8px;
            border-radius: 4px;
            color: #805ad5;
            font-family: 'Courier New', monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Email Template Testing</h1>
        
        <div class="info-box">
            <h3>⚙️ Setup Instructions</h3>
            <p><strong>1. Configure Mailtrap:</strong> Update your <code>.env</code> file with Mailtrap credentials:</p>
            <p style="margin-left: 20px;">
                <code>MAIL_MAILER=smtp</code><br>
                <code>MAIL_HOST=sandbox.smtp.mailtrap.io</code><br>
                <code>MAIL_PORT=2525</code><br>
                <code>MAIL_USERNAME=your_username</code><br>
                <code>MAIL_PASSWORD=your_password</code>
            </p>
            <p><strong>2. Seed Database:</strong> Make sure you have appointments and users: <code>php artisan db:seed --class=RealisticEuropeanSeeder</code></p>
            <p><strong>3. Click on any card below</strong> to send a test email and check your Mailtrap inbox!</p>
        </div>

        <div class="email-grid">
            <a href="/test-email/appointment-booked" class="email-card">
                <span class="email-icon">📅</span>
                <h2>Appointment Booked</h2>
                <p>Sent when a customer books a new appointment. Includes appointment details and confirmation status.</p>
                <span class="test-btn">Test Email</span>
            </a>

            <a href="/test-email/appointment-confirmed" class="email-card">
                <span class="email-icon">✅</span>
                <h2>Appointment Confirmed</h2>
                <p>Sent when provider confirms the appointment. Reminds customer to complete payment.</p>
                <span class="test-btn">Test Email</span>
            </a>

            <a href="/test-email/payment-completed" class="email-card">
                <span class="email-icon">💳</span>
                <h2>Payment Completed</h2>
                <p>Sent after successful payment. Includes receipt details and transaction information.</p>
                <span class="test-btn">Test Email</span>
            </a>

            <a href="/test-email/service-completed" class="email-card">
                <span class="email-icon">🎉</span>
                <h2>Service Completed</h2>
                <p>Sent when appointment is marked complete. Requests customer review and feedback.</p>
                <span class="test-btn">Test Email</span>
            </a>

            <a href="/test-email/verify-email" class="email-card">
                <span class="email-icon">📧</span>
                <h2>Verify Email</h2>
                <p>Sent during registration. Contains verification link to activate user account.</p>
                <span class="test-btn">Test Email</span>
            </a>

            <a href="/test-email/forgot-password" class="email-card">
                <span class="email-icon">🔐</span>
                <h2>Forgot Password</h2>
                <p>Sent when user requests password reset. Contains secure reset link with expiration.</p>
                <span class="test-btn">Test Email</span>
            </a>
        </div>
    </div>
</body>
</html>
