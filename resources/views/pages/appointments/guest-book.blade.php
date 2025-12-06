@extends('layouts.app')

@section('title', 'Book Appointment - ' . $provider->name)

@push('styles')
<style>
    .booking-hero {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-1) 50%, var(--primary-2) 100%);
        padding: 3rem 0 5rem;
        position: relative;
        overflow: hidden;
    }
    
    .booking-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
        opacity: 0.5;
    }
    
    .booking-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: var(--white);
    }
    
    .booking-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .booking-subtitle {
        font-size: 1.125rem;
        opacity: 0.9;
    }
    
    .booking-card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        box-shadow: var(--shadow-2xl);
        padding: 3rem;
        margin-top: -3rem;
        position: relative;
        z-index: 10;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        width: 100%;
        padding: 0.875rem 1.25rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-lg);
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary-2);
        box-shadow: 0 0 0 4px rgba(190, 49, 68, 0.1);
    }
    
    .btn-book {
        width: 100%;
        padding: 1.125rem 2rem;
        background: var(--gradient-primary);
        color: var(--white);
        border: none;
        border-radius: var(--radius-xl);
        font-size: 1.125rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(190, 49, 68, 0.3);
    }
    
    .btn-book:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(190, 49, 68, 0.4);
    }
    
    .provider-info-card {
        background: var(--gray-50);
        border-radius: var(--radius-xl);
        padding: 2rem;
        margin-bottom: 2rem;
    }
    
    .provider-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--white);
        box-shadow: var(--shadow-lg);
    }
    
    .service-card {
        background: var(--white);
        border: 2px solid var(--primary-2);
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .alert {
        padding: 1rem 1.5rem;
        border-radius: var(--radius-lg);
        margin-bottom: 1.5rem;
    }
    
    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border: 2px solid var(--danger);
        color: var(--danger);
    }
</style>
@endpush

@section('content')
<!-- Booking Hero -->
<section class="booking-hero">
    <div class="booking-hero-content">
        <div class="container">
            <h1 class="booking-title">Book Appointment</h1>
            <p class="booking-subtitle">Complete the form below to book your appointment</p>
        </div>
    </div>
</section>

<!-- Booking Form -->
<section class="section-light" style="padding-top: 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="booking-card">
                    @if($errors->any())
                        <div class="alert alert-error">
                            <strong>Error:</strong> {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- Provider Info -->
                    <div class="provider-info-card">
                        <div class="d-flex align-items-center gap-3">
                            @if($provider->photo)
                                <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}" class="provider-avatar">
                            @else
                                <div class="provider-avatar" style="background: var(--gradient-coral); display: flex; align-items: center; justify-content: center; color: var(--white); font-weight: 700; font-size: 1.5rem;">
                                    {{ strtoupper(substr($provider->name, 0, 2)) }}
                                </div>
                            @endif
                            <div>
                                <h3 style="margin: 0; font-size: 1.5rem; color: var(--primary-dark);">{{ $provider->name }}</h3>
                                <p style="margin: 0; color: var(--gray-600);">{{ $provider->expertise }}</p>
                                @if($provider->average_rating)
                                    <div style="margin-top: 0.5rem; color: var(--warning);">
                                        <i class="bi bi-star-fill"></i> {{ number_format($provider->average_rating, 1) }}
                                        <span style="color: var(--gray-500);">({{ $provider->total_reviews }} reviews)</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($service)
                        <!-- Selected Service -->
                        <div class="service-card">
                            <h4 style="margin: 0 0 0.5rem 0; color: var(--primary-2);">Selected Service</h4>
                            <p style="margin: 0; font-size: 1.125rem; font-weight: 600;">{{ $service->name }}</p>
                            <div style="display: flex; gap: 1.5rem; margin-top: 0.75rem; color: var(--gray-600);">
                                <span><i class="bi bi-clock"></i> {{ $service->duration }} mins</span>
                                <span><i class="bi bi-currency-dollar"></i> {{ App\Facades\Settings::formatPrice($service->price) }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Booking Form -->
                    <form action="{{ route('guest.appointments.store', $provider) }}" method="POST">
                        @csrf

                        <h4 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Information</h4>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="Enter your full name">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address *</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="your.email@example.com">
                                <small style="color: var(--gray-500); display: block; margin-top: 0.25rem;">
                                    We'll send your login credentials to this email
                                </small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" value="{{ old('phone') }}" required placeholder="+880 1234-567890">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Service *</label>
                                <select name="service_id" class="form-control" required>
                                    <option value="">Select a service</option>
                                    @foreach($provider->services as $providerService)
                                        <option value="{{ $providerService->id }}" 
                                            {{ ($service && $service->id == $providerService->id) || old('service_id') == $providerService->id ? 'selected' : '' }}>
                                            {{ $providerService->name }} - {{ App\Facades\Settings::formatPrice($providerService->price) }} ({{ $providerService->duration }} mins)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <h4 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Appointment Details</h4>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Appointment Date *</label>
                                <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date') }}" 
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Preferred Time *</label>
                                <input type="time" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Additional Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Any special requests or notes...">{{ old('notes') }}</textarea>
                        </div>

                        <div style="background: var(--gray-50); padding: 1.5rem; border-radius: var(--radius-lg); margin-bottom: 2rem;">
                            <h5 style="margin: 0 0 0.75rem 0; color: var(--primary-dark);">
                                <i class="bi bi-info-circle"></i> Important Information
                            </h5>
                            <ul style="margin: 0; padding-left: 1.25rem; color: var(--gray-700);">
                                <li>If you're a new customer, we'll create an account for you</li>
                                <li>Login credentials will be sent to your email</li>
                                <li>You can use your account to manage appointments</li>
                                <li>Please arrive 5 minutes early for your appointment</li>
                            </ul>
                        </div>

                        <button type="submit" class="btn-book">
                            <i class="bi bi-calendar-check"></i> Book Appointment
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
