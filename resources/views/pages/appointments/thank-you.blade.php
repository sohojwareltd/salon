@extends('layouts.app')

@section('content')
<div class="thank-you-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Success Card -->
                <div class="success-card">
                    <!-- Success Icon -->
                    <div class="success-icon" aria-hidden="true">
                        <i class="bi bi-check-lg"></i>
                    </div>

                    <!-- Success Message -->
                    <h1 class="title">
                        Booking Confirmed!
                    </h1>
                    <p class="subtitle">
                        Your appointment has been successfully booked. We've sent you a confirmation with all the details.
                    </p>

                    <!-- Appointment Details -->
                    @if(session('appointment'))
                    <div class="appointment-details">
                        <h3 class="section-title">
                            <i class="bi bi-calendar-check me-2"></i>Appointment Details
                        </h3>

                        <div class="row g-3">
                            <!-- Provider -->
                            <div class="col-12">
                                <div class="provider-box">
                                    <div class="provider-avatar" aria-hidden="true">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div class="provider-info">
                                        <div class="label">Provider</div>
                                        <div class="value">{{ session('appointment')->provider->user->name ?? 'Provider' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="col-12 col-md-6">
                                <div class="details-item">
                                    <div class="details-label">
                                        <i class="bi bi-calendar3 me-1"></i>Date
                                    </div>
                                    <div class="details-value">
                                        {{ \Carbon\Carbon::parse(session('appointment')->appointment_date)->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="details-item">
                                    <div class="details-label">
                                        <i class="bi bi-clock me-1"></i>Time
                                    </div>
                                    <div class="details-value">
                                        {{ \Carbon\Carbon::parse(session('appointment')->start_time, 'UTC')->format('h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Services -->
                            <div class="col-12">
                                <div class="details-item">
                                    <div class="details-label">
                                        <i class="bi bi-scissors me-1"></i>Services ({{ session('appointment')->services->count() }})
                                    </div>
                                    <div class="services-list">
                                        @foreach(session('appointment')->services as $service)
                                        <div class="service-item">
                                            <span class="service-name">{{ $service->name }}</span>
                                            <span class="service-price">{{ App\Facades\Settings::formatPrice($service->price, false) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="col-12">
                                <div class="total-amount">
                                    <span class="total-label">Total Amount</span>
                                    <span class="total-value">{{ App\Facades\Settings::formatPrice(session('appointment')->total_amount, false) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Guest User Credentials Notice -->
                    @guest
                    @if(session('isNewGuestUser') && session('guestPassword'))
                    <div class="guest-credentials">
                        <div class="guest-credentials-header">
                            <i class="bi bi-shield-check-fill" aria-hidden="true"></i>
                            <div class="flex-1">
                                <h4 class="guest-credentials-title">
                                    🎉 Account Created Successfully!
                                </h4>
                                <p class="guest-credentials-subtitle">
                                    Your account has been created. Save these credentials to login and manage your appointments.
                                </p>
                            </div>
                        </div>
                        
                        <!-- Credentials Box -->
                        <div class="guest-credentials-box">
                            <div class="mb-3">
                                <label class="input-label">
                                    📧 Email Address
                                </label>
                                <div class="copy-row">
                                    <span class="copy-value">{{ session('appointment')->customer->email }}</span>
                                    <button type="button" class="copy-btn" onclick="copyToClipboard(this, '{{ session('appointment')->customer->email }}')">
                                        <i class="bi bi-clipboard"></i> <span class="btn-text">Copy</span>
                                    </button>
                                </div>
                            </div>
                            
                            <div>
                                <label class="input-label">
                                    🔑 Password
                                </label>
                                <div class="copy-row">
                                    <span class="copy-value">{{ session('guestPassword') }}</span>
                                    <button type="button" class="copy-btn" onclick="copyToClipboard(this, '{{ session('guestPassword') }}')">
                                        <i class="bi bi-clipboard"></i> <span class="btn-text">Copy</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="note-box">
                            <p>
                                <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                                <strong>Note:</strong> We've also sent these credentials to your email. Please change your password after first login.
                            </p>
                        </div>
                    </div>
                    @endif
                    @endguest

                    <!-- Action Buttons -->
                    <div class="action-buttons">
                        @auth
                        <a href="{{ route('customer.bookings') }}" class="btn-cta btn-cta-primary">
                            <i class="bi bi-calendar-check"></i>
                            View My Appointments
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="btn-cta btn-cta-primary">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login to Your Account
                        </a>
                        @endauth
                        <a href="{{ route('home') }}" class="btn-cta btn-cta-secondary">
                            <i class="bi bi-house"></i>
                            Back to Home
                        </a>
                    </div>

                    <!-- Additional Info -->
                    <div class="additional-info">
                        <div class="alert-note">
                            <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
                            <div class="flex-1">
                                <p>
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
/* ===== RESPONSIVE PAGE WRAPPER ===== */
.thank-you-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    padding: 48px 16px;
}

@media (max-width: 768px) {
    .thank-you-page { 
        padding: 40px 12px; 
    }
}

@media (max-width: 640px) {
    .thank-you-page { 
        padding: 32px 12px; 
    }
}

@media (max-width: 480px) {
    .thank-you-page { 
        padding: 24px 10px; 
    }
}

@media (min-width: 576px) {
    .thank-you-page { padding: 60px 20px; }
}

/* ===== RESPONSIVE CARD ===== */
.success-card {
    background: #ffffff;
    border-radius: 24px;
    padding: 32px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.08);
}

@media (max-width: 768px) {
    .success-card { 
        padding: 24px 20px;
        border-radius: 20px;
    }
}

@media (max-width: 640px) {
    .success-card { 
        padding: 20px 16px;
        border-radius: 18px;
    }
}

@media (max-width: 480px) {
    .success-card { 
        padding: 16px 12px;
        border-radius: 16px;
    }
}

@media (min-width: 576px) {
    .success-card { padding: 48px 32px; }
}

.success-icon {
    width: 90px;
    height: 90px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #10b981, #059669);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: scaleIn 0.5s ease;
}
.success-icon i {
    font-size: 48px;
    color: #fff;
    font-weight: bold;
}

@media (max-width: 768px) {
    .success-icon { 
        width: 80px; 
        height: 80px; 
        margin: 0 auto 16px; 
    }
    .success-icon i { font-size: 40px; }
}

@media (max-width: 640px) {
    .success-icon { 
        width: 70px; 
        height: 70px; 
        margin: 0 auto 12px; 
    }
    .success-icon i { font-size: 36px; }
}

@media (max-width: 480px) {
    .success-icon { 
        width: 60px; 
        height: 60px; 
        margin: 0 auto 12px; 
    }
    .success-icon i { font-size: 32px; }
}

@media (min-width: 576px) {
    .success-icon { width: 100px; height: 100px; margin-bottom: 24px; }
    .success-icon i { font-size: 56px; }
}

.title { font-size: 26px; font-weight: 800; color: #065f46; margin-bottom: 12px; }
.subtitle { font-size: 15px; color: #6b7280; line-height: 1.6; margin-bottom: 24px; }

@media (max-width: 768px) {
    .title { font-size: 24px; margin-bottom: 10px; }
    .subtitle { font-size: 14px; margin-bottom: 20px; }
}

@media (max-width: 640px) {
    .title { font-size: 20px; margin-bottom: 8px; }
    .subtitle { font-size: 13px; margin-bottom: 16px; }
}

@media (max-width: 480px) {
    .title { font-size: 18px; margin-bottom: 8px; }
    .subtitle { font-size: 12px; margin-bottom: 12px; }
}

@media (min-width: 576px) {
    .title { font-size: 32px; margin-bottom: 16px; }
    .subtitle { font-size: 16px; margin-bottom: 32px; }
}

/* ===== APPOINTMENT DETAILS - RESPONSIVE ===== */
.appointment-details {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 24px;
    text-align: left;
}

@media (max-width: 768px) {
    .appointment-details { 
        padding: 16px; 
        margin-bottom: 20px;
    }
}

@media (max-width: 640px) {
    .appointment-details { 
        padding: 14px; 
        margin-bottom: 16px;
        border-radius: 12px;
    }
}

@media (max-width: 480px) {
    .appointment-details { 
        padding: 12px; 
        margin-bottom: 12px;
        border-radius: 10px;
    }
}

@media (min-width: 576px) {
    .appointment-details { padding: 24px; margin-bottom: 32px; }
}

.section-title {
    font-size: 16px; 
    font-weight: 700; 
    color: #065f46; 
    margin-bottom: 16px; 
    text-align: center;
}

@media (max-width: 768px) {
    .section-title { 
        font-size: 15px; 
        margin-bottom: 12px; 
    }
}

@media (max-width: 640px) {
    .section-title { 
        font-size: 14px; 
        margin-bottom: 10px; 
    }
}

@media (min-width: 576px) { 
    .section-title { font-size: 18px; margin-bottom: 20px; } 
}

.provider-box {
    display: flex; align-items: center; gap: 12px; padding: 12px; background: #fff; border-radius: 12px;
}
.provider-avatar {
    width: 44px; height: 44px; border-radius: 50%; background: linear-gradient(135deg, #872341, #BE3144);
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.provider-avatar i { font-size: 22px; color: #fff; }
@media (min-width: 576px) {
    .provider-avatar { width: 48px; height: 48px; }
    .provider-avatar i { font-size: 24px; }
}
.provider-info .label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; }
.provider-info .value { font-size: 15px; font-weight: 600; color: #111827; }
@media (min-width: 576px) { .provider-info .value { font-size: 16px; } }

.details-item { padding: 12px; background: #fff; border-radius: 12px; }
.details-label { font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
.details-value { font-size: 14px; font-weight: 700; color: #065f46; }
@media (min-width: 576px) { .details-value { font-size: 15px; } }

.services-list { display: flex; flex-direction: column; gap: 6px; }
.service-item { display: flex; justify-content: space-between; align-items: center; }
.service-name { font-size: 14px; color: #111827; font-weight: 500; }
.service-price { font-size: 13px; color: #10b981; font-weight: 600; }

.total-amount {
    padding: 14px; background: linear-gradient(135deg, #065f46, #047857); border-radius: 12px;
    display: flex; justify-content: space-between; align-items: center;
}
.total-label { font-size: 12px; color: #d1fae5; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }
.total-value { font-size: 20px; color: #fff; font-weight: 800; }
@media (min-width: 576px) {
    .total-amount { padding: 16px; }
    .total-value { font-size: 24px; }
}

/* Guest credentials */
.guest-credentials {
    margin-bottom: 20px; padding: 20px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(37, 99, 235, 0.1));
    border-radius: 16px; border: 2px solid rgba(59, 130, 246, 0.3); text-align: left;
}
@media (min-width: 576px) { .guest-credentials { margin-bottom: 24px; padding: 24px; } }
.guest-credentials-header { display: flex; align-items: start; gap: 12px; margin-bottom: 12px; }
.guest-credentials-header i { font-size: 24px; color: #3b82f6; margin-top: 2px; }
@media (min-width: 576px) { .guest-credentials-header i { font-size: 28px; } }
.guest-credentials-title { font-size: 16px; font-weight: 700; color: #1e40af; margin-bottom: 6px; }
.guest-credentials-subtitle { font-size: 13px; color: #1e3a8a; margin: 0; line-height: 1.6; }
@media (min-width: 576px) {
    .guest-credentials-title { font-size: 18px; margin-bottom: 8px; }
    .guest-credentials-subtitle { font-size: 14px; }
}
.guest-credentials-box { background: #fff; border-radius: 12px; padding: 16px; margin-top: 12px; }
@media (min-width: 576px) { .guest-credentials-box { padding: 20px; margin-top: 16px; } }
.input-label { font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; display: block; margin-bottom: 6px; }
.copy-row { background: #f3f4f6; padding: 10px 12px; border-radius: 8px; font-family: 'Courier New', monospace; font-size: 14px; font-weight: 600; color: #111827; display: flex; justify-content: space-between; align-items: center; gap: 8px; }
@media (min-width: 576px) { .copy-row { padding: 12px 16px; font-size: 15px; } }
.copy-value { overflow-wrap: anywhere; }
.copy-btn { background: #3b82f6; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 12px; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; }
.copy-btn.success { background: #10b981; }
.copy-btn:focus { outline: 2px solid #93c5fd; outline-offset: 2px; }

.note-box { margin-top: 12px; padding: 12px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b; }
.note-box p { font-size: 13px; color: #92400e; margin: 0; }
.note-box i { color: #f59e0b; }

/* Actions */
.action-buttons { display: flex; gap: 12px; margin-top: 24px; flex-wrap: wrap; }
@media (min-width: 576px) { .action-buttons { margin-top: 32px; } }
.btn-cta { flex: 1; padding: 12px 20px; border-radius: 12px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease; }
.btn-cta-primary { background: linear-gradient(135deg, #872341, #BE3144); color: #fff; }
.btn-cta-secondary { background: #f3f4f6; color: #374151; }
@media (max-width: 575.98px) { .btn-cta { width: 100%; } }

/* Additional info */
.additional-info { margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb; }
@media (min-width: 576px) { .additional-info { margin-top: 32px; padding-top: 32px; } }
.alert-note { display: flex; align-items: start; gap: 12px; text-align: left; background: #fef3c7; padding: 16px; border-radius: 12px; border-left: 4px solid #f59e0b; }
.alert-note i { font-size: 18px; color: #f59e0b; margin-top: 2px; }
.alert-note p { font-size: 13px; color: #92400e; margin: 0; line-height: 1.5; }
@media (min-width: 576px) { .alert-note i { font-size: 20px; } .alert-note p { font-size: 14px; } }

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
.btn-cta:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
</style>

<script>
function copyToClipboard(buttonEl, text) {
    const fallbackCopy = () => {
        try {
            const tempInput = document.createElement('input');
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand('copy');
            document.body.removeChild(tempInput);
            return Promise.resolve();
        } catch (e) {
            return Promise.reject(e);
        }
    };

    const doCopy = navigator.clipboard && navigator.clipboard.writeText
        ? navigator.clipboard.writeText(text)
        : fallbackCopy();

    doCopy.then(function() {
        const originalHTML = buttonEl.innerHTML;
        buttonEl.classList.add('success');
        buttonEl.innerHTML = '<i class="bi bi-check"></i> <span class="btn-text">Copied!</span>';
        setTimeout(function() {
            buttonEl.innerHTML = originalHTML;
            buttonEl.classList.remove('success');
        }, 2000);
    }).catch(function(err) {
        alert('Failed to copy: ' + err);
    });
}
</script>
@endsection
