@extends('layouts.app')

@section('title', 'Login - Premium Salon Booking')

@push('styles')
<style>
    .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-1) 50%, var(--primary-2) 100%);
        position: relative;
        overflow: hidden;
        padding: 2rem 1rem;
    }
    
    .auth-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
        opacity: 0.5;
    }
    
    .auth-floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    }
    
    .auth-shape {
        position: absolute;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        animation: float-shapes 20s infinite ease-in-out;
    }
    
    .auth-shape:nth-child(1) {
        width: 300px;
        height: 300px;
        top: -100px;
        left: -100px;
        animation-delay: 0s;
    }
    
    .auth-shape:nth-child(2) {
        width: 200px;
        height: 200px;
        bottom: -50px;
        right: -50px;
        animation-delay: 5s;
    }
    
    .auth-shape:nth-child(3) {
        width: 150px;
        height: 150px;
        top: 50%;
        right: 10%;
        animation-delay: 10s;
    }
    
    @keyframes float-shapes {
        0%, 100% { transform: translate(0, 0) rotate(0deg); }
        33% { transform: translate(30px, -30px) rotate(120deg); }
        66% { transform: translate(-20px, 20px) rotate(240deg); }
    }
    
    .auth-card {
        position: relative;
        z-index: 10;
        background: var(--white);
        border-radius: var(--radius-2xl);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        max-width: 480px;
        width: 100%;
        overflow: hidden;
        animation: slideInUp 0.8s ease-out;
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .auth-header {
        text-align: center;
        padding: 3rem 3rem 2rem;
        background: linear-gradient(180deg, rgba(190, 49, 68, 0.05) 0%, transparent 100%);
    }
    
    .auth-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: var(--gradient-primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: var(--white);
        box-shadow: 0 8px 24px rgba(190, 49, 68, 0.3);
        animation: pulse-icon 2s ease-in-out infinite;
    }
    
    @keyframes pulse-icon {
        0%, 100% { transform: scale(1); box-shadow: 0 8px 24px rgba(190, 49, 68, 0.3); }
        50% { transform: scale(1.05); box-shadow: 0 12px 32px rgba(190, 49, 68, 0.4); }
    }
    
    .auth-title {
        font-size: 2rem;
        font-weight: 700;
        font-family: var(--font-heading);
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }
    
    .auth-subtitle {
        color: var(--gray-600);
        font-size: 1rem;
    }
    
    .auth-subtitle a {
        color: var(--primary-2);
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .auth-subtitle a:hover {
        color: var(--primary-1);
        text-decoration: underline;
    }
    
    .auth-body {
        padding: 2rem 3rem 3rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
        font-size: 0.9375rem;
    }
    
    .form-label i {
        color: var(--primary-2);
    }
    
    .form-input-wrapper {
        position: relative;
    }
    
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem 0.875rem 3rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-xl);
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
    }
    
    .form-input:focus {
        outline: none;
        border-color: var(--primary-2);
        box-shadow: 0 0 0 4px rgba(190, 49, 68, 0.1);
    }
    
    .form-input.error {
        border-color: var(--danger);
    }
    
    .form-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--gray-400);
        font-size: 1.125rem;
        transition: color 0.3s ease;
    }
    
    .form-input:focus ~ .form-icon {
        color: var(--primary-2);
    }
    
    .form-error {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .form-checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        justify-content: space-between;
        margin: 1.5rem 0;
    }
    
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
    }
    
    .form-checkbox {
        width: 18px;
        height: 18px;
        border: 2px solid var(--gray-300);
        border-radius: 4px;
        cursor: pointer;
        accent-color: var(--primary-2);
    }
    
    .forgot-link {
        color: var(--primary-2);
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .forgot-link:hover {
        color: var(--primary-1);
        text-decoration: underline;
    }
    
    .btn-auth {
        width: 100%;
        padding: 1rem 2rem;
        background: var(--gradient-primary);
        color: var(--white);
        border: none;
        border-radius: var(--radius-xl);
        font-size: 1.0625rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(190, 49, 68, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .btn-auth::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .btn-auth:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(190, 49, 68, 0.4);
    }
    
    .btn-auth:active {
        transform: translateY(0);
    }
    
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 2rem 0;
        color: var(--gray-500);
        font-size: 0.875rem;
    }
    
    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .divider span {
        padding: 0 1rem;
    }
    
    .social-login {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .btn-social {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-lg);
        background: var(--white);
        color: var(--gray-700);
        font-weight: 600;
        font-size: 0.9375rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .btn-social:hover {
        border-color: var(--primary-2);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    @media (max-width: 576px) {
        .auth-header {
            padding: 2rem 1.5rem 1.5rem;
        }
        
        .auth-body {
            padding: 1.5rem;
        }
        
        .auth-title {
            font-size: 1.75rem;
        }
        
        .auth-icon {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }
        
        .social-login {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-floating-shapes">
        <div class="auth-shape"></div>
        <div class="auth-shape"></div>
        <div class="auth-shape"></div>
    </div>
    
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="bi bi-scissors"></i>
            </div>
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">
                Don't have an account? 
                <a href="{{ route('register') }}">Create one now</a>
            </p>
        </div>
        
        <div class="auth-body">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">
                        <i class="bi bi-envelope"></i>
                        Email Address
                    </label>
                    <div class="form-input-wrapper">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email" 
                            autofocus
                            placeholder="Enter your email"
                            class="form-input @error('email') error @enderror"
                        >
                        <i class="bi bi-envelope form-icon"></i>
                    </div>
                    @error('email')
                        <p class="form-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="bi bi-lock"></i>
                        Password
                    </label>
                    <div class="form-input-wrapper">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            required 
                            autocomplete="current-password"
                            placeholder="Enter your password"
                            class="form-input @error('password') error @enderror"
                        >
                        <i class="bi bi-lock form-icon"></i>
                    </div>
                    @error('password')
                        <p class="form-error">
                            <i class="bi bi-exclamation-circle"></i>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="form-checkbox-wrapper">
                    <label class="checkbox-label">
                        <input 
                            id="remember" 
                            name="remember" 
                            type="checkbox" 
                            {{ old('remember') ? 'checked' : '' }}
                            class="form-checkbox"
                        >
                        <span style="color: var(--gray-700); font-size: 0.9375rem;">Remember me</span>
                    </label>

                    {{-- @if (Route::has('password.request')) --}}
                        <a href="{{ route('password.request') }}" class="forgot-link">
                            Forgot Password?
                        </a>
                    {{-- @endif --}}
                </div>

                <button type="submit" class="btn-auth">
                    <i class="bi bi-box-arrow-in-right"></i>
                    Sign In
                </button>
            </form>
            
            <div class="divider">
                <span>Or continue with</span>
            </div>
            
            <div class="social-login">
                <button class="btn-social" type="button">
                    <i class="bi bi-google" style="color: #DB4437;"></i>
                    Google
                </button>
                <button class="btn-social" type="button">
                    <i class="bi bi-facebook" style="color: #4267B2;"></i>
                    Facebook
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
