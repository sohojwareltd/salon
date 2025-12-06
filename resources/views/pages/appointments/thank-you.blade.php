@extends('layouts.app')

@section('content')
<div class="min-vh-100" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 60px 20px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Success Card -->
                <div style="background: white; border-radius: 24px; padding: 48px 32px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.08);">
                    <!-- Success Icon -->
                    <div style="width: 100px; height: 100px; margin: 0 auto 24px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: scaleIn 0.5s ease;">
                        <i class="bi bi-check-lg" style="font-size: 56px; color: white; font-weight: bold;"></i>
                    </div>

                    <!-- Success Message -->
                    <h1 style="font-size: 32px; font-weight: 800; color: #065f46; margin-bottom: 16px;">
                        Booking Confirmed!
                    </h1>
                    <p style="font-size: 16px; color: #6b7280; line-height: 1.6; margin-bottom: 32px;">
                        Your appointment has been successfully booked. We've sent you a confirmation with all the details.
                    </p>

                    <!-- Appointment Details -->
                    @if(session('appointment'))
                    <div style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-radius: 16px; padding: 24px; margin-bottom: 32px; text-align: left;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #065f46; margin-bottom: 20px; text-align: center;">
                            <i class="bi bi-calendar-check me-2"></i>Appointment Details
                        </h3>

                        <div class="row g-3">
                            <!-- Provider -->
                            <div class="col-12">
                                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 12px;">
                                    <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #872341, #BE3144); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="bi bi-person-fill" style="font-size: 24px; color: white;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Provider</div>
                                        <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ session('appointment')->provider->user->name ?? 'Provider' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="col-6">
                                <div style="padding: 12px; background: white; border-radius: 12px;">
                                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                        <i class="bi bi-calendar3 me-1"></i>Date
                                    </div>
                                    <div style="font-size: 15px; font-weight: 700; color: #065f46;">
                                        {{ \Carbon\Carbon::parse(session('appointment')->appointment_date)->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="padding: 12px; background: white; border-radius: 12px;">
                                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                        <i class="bi bi-clock me-1"></i>Time
                                    </div>
                                    <div style="font-size: 15px; font-weight: 700; color: #065f46;">
                                        {{ \Carbon\Carbon::parse(session('appointment')->start_time, 'UTC')->format('h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Services -->
                            <div class="col-12">
                                <div style="padding: 12px; background: white; border-radius: 12px;">
                                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                                        <i class="bi bi-scissors me-1"></i>Services ({{ session('appointment')->services->count() }})
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        @foreach(session('appointment')->services as $service)
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 14px; color: #111827; font-weight: 500;">{{ $service->name }}</span>
                                            <span style="font-size: 13px; color: #10b981; font-weight: 600;">{{ App\Facades\Settings::formatPrice($service->price, false) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="col-12">
                                <div style="padding: 16px; background: linear-gradient(135deg, #065f46, #047857); border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 14px; color: #d1fae5; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Total Amount</span>
                                    <span style="font-size: 24px; color: white; font-weight: 800;">{{ App\Facades\Settings::formatPrice(session('appointment')->total_amount, false) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Guest User Credentials Notice -->
                    @guest
                    @if(session('isNewGuestUser') && session('guestPassword'))
                    <div style="margin-bottom: 24px; padding: 24px; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1)); border-radius: 16px; border: 2px solid rgba(59, 130, 246, 0.3); text-align: left;">
                        <div style="display: flex; align-items-start; gap: 12px; margin-bottom: 16px;">
                            <i class="bi bi-shield-check-fill" style="font-size: 28px; color: #3b82f6; margin-top: 2px;"></i>
                            <div style="flex: 1;">
                                <h4 style="font-size: 18px; font-weight: 700; color: #1e40af; margin-bottom: 8px;">
                                    🎉 Account Created Successfully!
                                </h4>
                                <p style="font-size: 14px; color: #1e3a8a; margin: 0; line-height: 1.6;">
                                    Your account has been created. Save these credentials to login and manage your appointments.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Credentials Box -->
                        <div style="background: white; border-radius: 12px; padding: 20px; margin-top: 16px;">
                            <div style="margin-bottom: 16px;">
                                <label style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; display: block; margin-bottom: 6px;">
                                    📧 Email Address
                                </label>
                                <div style="background: #f3f4f6; padding: 12px 16px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 15px; font-weight: 600; color: #111827; display: flex; justify-content: space-between; align-items: center;">
                                    <span>{{ session('appointment')->customer->email }}</span>
                                    <button onclick="copyToClipboard('{{ session('appointment')->customer->email }}')" style="background: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; display: block; margin-bottom: 6px;">
                                    🔑 Password
                                </label>
                                <div style="background: #f3f4f6; padding: 12px 16px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 15px; font-weight: 600; color: #111827; display: flex; justify-content: space-between; align-items: center;">
                                    <span>{{ session('guestPassword') }}</span>
                                    <button onclick="copyToClipboard('{{ session('guestPassword') }}')" style="background: #3b82f6; color: white; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer;">
                                        <i class="bi bi-clipboard"></i> Copy
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div style="margin-top: 16px; padding: 12px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
                            <p style="font-size: 13px; color: #92400e; margin: 0;">
                                <i class="bi bi-info-circle-fill" style="color: #f59e0b;"></i>
                                <strong>Note:</strong> We've also sent these credentials to your email. Please change your password after first login.
                            </p>
                        </div>
                    </div>
                    @endif
                    @endguest

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        @auth
                        <a href="{{ route('customer.bookings') }}" style="flex: 1; padding: 14px 24px; background: linear-gradient(135deg, #872341, #BE3144); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s;">
                            <i class="bi bi-calendar-check"></i>
                            View My Appointments
                        </a>
                        @else
                        <a href="{{ route('login') }}" style="flex: 1; padding: 14px 24px; background: linear-gradient(135deg, #872341, #BE3144); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s;">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login to Your Account
                        </a>
                        @endauth
                        <a href="{{ route('home') }}" style="flex: 1; padding: 14px 24px; background: #f3f4f6; color: #374151; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s;">
                            <i class="bi bi-house"></i>
                            Back to Home
                        </a>
                    </div>

                    <!-- Additional Info -->
                    <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid #e5e7eb;">
                        <div style="display: flex; align-items: start; gap: 12px; text-align: left; background: #fef3c7; padding: 16px; border-radius: 12px; border-left: 4px solid #f59e0b;">
                            <i class="bi bi-info-circle-fill" style="font-size: 20px; color: #f59e0b; margin-top: 2px;"></i>
                            <div style="flex: 1;">
                                <p style="font-size: 14px; color: #92400e; margin: 0; line-height: 1.5;">
                                    <strong>Please Note:</strong> Your appointment is currently <strong>pending</strong>. The provider will confirm it shortly. You'll receive a notification once confirmed.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scaleIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

a:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
</style>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const btn = event.target.closest('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
        btn.style.background = '#10b981';
        
        setTimeout(function() {
            btn.innerHTML = originalText;
            btn.style.background = '#3b82f6';
        }, 2000);
    }).catch(function(err) {
        alert('Failed to copy: ' + err);
    });
}
</script>
@endsection
