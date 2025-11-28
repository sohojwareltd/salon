@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="verify-email-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <!-- Verification Card -->
                <div class="verification-card">
                    <!-- Icon Header -->
                    <div class="verification-header">
                        <div class="icon-wrapper">
                            <i class="bi bi-envelope-check"></i>
                        </div>
                        <h2>Verify Your Email</h2>
                        <p class="subtitle">We've sent a verification link to your inbox</p>
                    </div>

                    <!-- Alert Messages -->
                    <div class="verification-body">
                        @if (session('resent'))
                            <div class="alert alert-success d-flex align-items-start mb-4 animate-fade-in" role="alert">
                                <i class="bi bi-check-circle-fill me-3 fs-5"></i>
                                <div>
                                    <strong>Verification Email Sent! 📧</strong>
                                    <p class="mb-0 mt-1">We've sent a fresh verification link to <strong>{{ auth()->user()->email }}</strong>. Please check your inbox and verify your email address to continue.</p>
                                    <small class="d-block mt-2 text-muted">⏰ This link will expire in 60 minutes for security reasons.</small>
                                </div>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
                                <i class="bi bi-info-circle-fill me-3 fs-5"></i>
                                <div>
                                    <p class="mb-0">{{ session('status') }}</p>
                                </div>
                            </div>
                        @endif

                <!-- Email Icon -->
                {{-- <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-pink-100 to-red-100 rounded-full">
                        <svg class="w-8 h-8 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                </div> --}}

                        <!-- Email Info -->
                        <div class="email-info-box">
                            <i class="bi bi-at"></i>
                            <div>
                                <strong>{{ auth()->user()->email }}</strong>
                                <small>Check your email for verification link</small>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <p class="text-center mb-4">
                            Before proceeding, please check your email for a verification link. Don't forget to check your spam folder if you don't see it in your inbox.
                        </p>

                        <!-- Divider -->
                        <div class="divider-text">
                            <span>Didn't receive the email?</span>
                        </div>

                        <!-- Resend Form -->
                        <form method="POST" action="{{ route('verification.resend') }}" id="resendForm">
                            @csrf
                            <button type="submit" id="resendButton" class="btn-primary w-100" style="position: relative;">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                <span id="buttonText">Resend Verification Email</span>
                            </button>
                        </form>

                        <!-- Countdown timer -->
                        <p class="text-center text-muted small mt-3" id="countdownText" style="display: none;">
                            You can resend the email in <span id="countdown" class="text-danger fw-bold">60</span> seconds
                        </p>

                        <!-- Security Info -->
                        <div class="security-info">
                            <i class="bi bi-shield-check"></i>
                            <small>This link will expire in 60 minutes for security reasons.</small>
                        </div>

                        <!-- Action Links -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-house"></i> Back to Home
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </div>

                        <!-- Help Text -->
                        <p class="text-center mt-4 small text-muted">
                            Having trouble? Contact us at 
                            <a href="mailto:support@salon.com" class="text-decoration-none">support@salon.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .verify-email-page {
        padding: 60px 0;
        min-height: calc(100vh - 160px);
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .verification-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .verification-header {
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        padding: 50px 30px;
        text-align: center;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .icon-wrapper {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        backdrop-filter: blur(10px);
    }

    .icon-wrapper i {
        font-size: 48px;
    }

    .verification-header h2 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .subtitle {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }

    .verification-body {
        padding: 40px;
    }

    .email-info-box {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #872341;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .email-info-box i {
        font-size: 32px;
        color: #872341;
    }

    .email-info-box strong {
        display: block;
        font-size: 16px;
        color: #333;
    }

    .email-info-box small {
        display: block;
        color: #666;
        font-size: 13px;
    }

    .divider-text {
        text-align: center;
        margin: 30px 0;
        position: relative;
    }

    .divider-text:before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #dee2e6;
    }

    .divider-text span {
        position: relative;
        background: white;
        padding: 0 15px;
        color: #6c757d;
        font-size: 14px;
    }

    .security-info {
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 8px;
        padding: 15px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .security-info i {
        font-size: 20px;
        color: #ffc107;
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #resendButton:disabled {
        opacity: 0.6;
        cursor: not-allowed !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resendButton = document.getElementById('resendButton');
        const buttonText = document.getElementById('buttonText');
        const countdownText = document.getElementById('countdownText');
        const countdownSpan = document.getElementById('countdown');
        const resendForm = document.getElementById('resendForm');
        
        let countdown = 60;
        let countdownInterval;
        
        // Check if there's a stored timestamp
        const resendTimestamp = localStorage.getItem('resendTimestamp');
        if (resendTimestamp) {
            const elapsed = Math.floor((Date.now() - parseInt(resendTimestamp)) / 1000);
            if (elapsed < 60) {
                countdown = 60 - elapsed;
                startCountdown();
            } else {
                localStorage.removeItem('resendTimestamp');
            }
        }
        
        // Check if resend was just triggered (from session)
        @if (session('resent'))
            localStorage.setItem('resendTimestamp', Date.now().toString());
            countdown = 60;
            startCountdown();
        @endif
        
        resendForm.addEventListener('submit', function(e) {
            if (resendButton.disabled) {
                e.preventDefault();
                return false;
            }
            
            // Store timestamp
            localStorage.setItem('resendTimestamp', Date.now().toString());
        });
        
        function startCountdown() {
            resendButton.disabled = true;
            countdownText.style.display = 'block';
            countdownSpan.textContent = countdown;
            
            countdownInterval = setInterval(function() {
                countdown--;
                countdownSpan.textContent = countdown;
                
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    resendButton.disabled = false;
                    countdownText.style.display = 'none';
                    localStorage.removeItem('resendTimestamp');
                    countdown = 60;
                }
            }, 1000);
        }
    });
</script>
@endsection
