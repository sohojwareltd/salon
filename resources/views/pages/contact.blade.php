@extends('layouts.app')

@section('title', 'Contact Us - Get In Touch')

@push('styles')
<style>
    /* ========================================
       HERO SECTION
    ======================================== */
    .contact-hero {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-1) 50%, var(--primary-2) 100%);
        padding: 5rem 0 8rem;
        position: relative;
        overflow: hidden;
    }
    
    .contact-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
        opacity: 0.5;
    }
    
    .contact-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: var(--white);
    }
    
    .contact-hero-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 2rem;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        animation: float 3s ease-in-out infinite;
    }
    
    .contact-hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        font-family: var(--font-heading);
        margin-bottom: 1rem;
        text-shadow: 2px 4px 12px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease-out;
    }
    
    .contact-hero-subtitle {
        font-size: 1.375rem;
        opacity: 0.95;
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    /* ========================================
       CONTACT FORM SECTION
    ======================================== */
    .contact-cards-wrapper {
        margin-top: -5rem;
        position: relative;
        z-index: 10;
    }
    
    .contact-form-card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        padding: 3rem;
        box-shadow: var(--shadow-2xl);
        animation: fadeInUp 0.8s ease-out 0.3s both;
        border: 2px solid var(--gray-100);
        transition: all 0.3s ease;
    }
    
    .contact-form-card:hover {
        border-color: var(--primary-2);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .form-title {
        font-size: 1.75rem;
        font-weight: 700;
        font-family: var(--font-heading);
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .form-title i {
        color: var(--primary-2);
        font-size: 1.5rem;
    }
    
    .form-description {
        color: var(--gray-600);
        margin-bottom: 2rem;
    }

    /* Success Message */
    .success-message {
        background: linear-gradient(135deg, rgba(34, 197, 94, 0.1) 0%, rgba(34, 197, 94, 0.05) 100%);
        border: 2px solid var(--success);
        border-radius: var(--radius-xl);
        padding: 1.25rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        animation: slideInDown 0.5s ease-out;
    }
    
    .success-message i {
        font-size: 1.5rem;
        color: var(--success);
    }
    
    .success-message-text {
        color: var(--success);
        font-weight: 600;
    }

    /* Form Elements */
    .form-group {
        margin-bottom: 1.75rem;
    }
    
    .form-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 0.625rem;
        font-size: 0.9375rem;
    }
    
    .form-label i {
        color: var(--primary-2);
        font-size: 1rem;
    }
    
    .form-input,
    .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-xl);
        font-size: 1rem;
        transition: all 0.3s ease;
        background: var(--white);
        font-family: inherit;
    }
    
    .form-input:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--primary-2);
        box-shadow: 0 0 0 4px rgba(190, 49, 68, 0.1);
    }
    
    .form-input.error,
    .form-textarea.error {
        border-color: var(--danger);
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 150px;
    }
    
    .form-error {
        color: var(--danger);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    /* Submit Button */
    .btn-submit {
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
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .btn-submit::before {
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
    
    .btn-submit:hover::before {
        width: 400px;
        height: 400px;
    }
    
    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(190, 49, 68, 0.4);
    }
    
    .btn-submit i {
        transition: transform 0.3s ease;
    }
    
    .btn-submit:hover i {
        transform: translateX(4px);
    }

    /* ========================================
       CONTACT INFO SECTION
    ======================================== */
    .contact-info-section {
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }
    
    .info-header {
        margin-bottom: 2rem;
    }
    
    .info-title {
        font-size: 2rem;
        font-weight: 700;
        font-family: var(--font-heading);
        color: var(--primary-dark);
        margin-bottom: 0.75rem;
    }
    
    .info-description {
        color: var(--gray-600);
        font-size: 1.0625rem;
        line-height: 1.7;
    }

    /* Info Cards */
    .info-cards {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    .info-card {
        background: var(--white);
        border-radius: var(--radius-xl);
        padding: 2rem;
        border: 2px solid var(--gray-100);
        transition: all 0.3s ease;
        display: flex;
        gap: 1.5rem;
    }
    
    .info-card:hover {
        border-color: var(--primary-2);
        transform: translateX(8px);
        box-shadow: var(--shadow-xl);
    }
    
    .info-card-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--radius-lg);
        background: var(--gradient-coral);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        flex-shrink: 0;
        box-shadow: 0 4px 16px rgba(225, 117, 100, 0.3);
        transition: transform 0.3s ease;
    }
    
    .info-card:hover .info-card-icon {
        transform: scale(1.1) rotate(5deg);
    }
    
    .info-card-content {
        flex: 1;
    }
    
    .info-card-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }
    
    .info-card-text {
        color: var(--gray-600);
        line-height: 1.6;
    }

    /* ========================================
       MAP SECTION
    ======================================== */
    .map-section {
        margin-top: 4rem;
        animation: fadeInUp 0.8s ease-out 0.5s both;
    }
    
    .map-card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        padding: 2rem;
        box-shadow: var(--shadow-xl);
        border: 2px solid var(--gray-100);
    }
    
    .map-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .map-header h3 {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: var(--font-heading);
        color: var(--primary-dark);
    }
    
    .map-header i {
        font-size: 1.5rem;
        color: var(--primary-2);
    }
    
    .map-placeholder {
        width: 100%;
        height: 400px;
        background: linear-gradient(135deg, var(--gray-100) 0%, var(--gray-200) 100%);
        border-radius: var(--radius-xl);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--gray-500);
        font-size: 1.125rem;
    }

    /* ========================================
       ANIMATIONS
    ======================================== */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        0%, 100% { 
            transform: translateY(0px); 
        }
        50% { 
            transform: translateY(-15px); 
        }
    }
    
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ========================================
       RESPONSIVE DESIGN
    ======================================== */
    @media (max-width: 768px) {
        .contact-hero {
            padding: 4rem 0 6rem;
        }
        
        .contact-hero-title {
            font-size: 2.5rem;
        }
        
        .contact-hero-icon {
            width: 80px;
            height: 80px;
            font-size: 2.5rem;
        }
        
        .contact-form-card {
            padding: 2rem 1.5rem;
        }
        
        .info-card {
            flex-direction: column;
            text-align: center;
        }
        
        .info-card-icon {
            margin: 0 auto;
        }
        
        .map-placeholder {
            height: 300px;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="contact-hero">
    <div class="contact-hero-content">
        <div class="container">
            <div class="contact-hero-icon">
                <i class="bi bi-chat-dots"></i>
            </div>
            <h1 class="contact-hero-title">Get In Touch</h1>
            <p class="contact-hero-subtitle">We'd love to hear from you. Send us a message!</p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="section-light" style="padding-top: 0;">
    <div class="container">
        <div class="contact-cards-wrapper">

            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-7">
                    <div class="contact-form-card">
                        <h2 class="form-title">
                            <i class="bi bi-envelope"></i>
                            Send Us a Message
                        </h2>
                        <p class="form-description">
                            Fill out the form below and we'll get back to you as soon as possible.
                        </p>
                        
                        @if(session('success'))
                            <div class="success-message">
                                <i class="bi bi-check-circle-fill"></i>
                                <span class="success-message-text">{{ session('success') }}</span>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            
                            <div class="form-group">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person"></i>
                                    Your Name
                                </label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required
                                    placeholder="Enter your full name"
                                    class="form-input @error('name') error @enderror"
                                >
                                @error('name')
                                    <p class="form-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope"></i>
                                    Email Address
                                </label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    value="{{ old('email') }}" 
                                    required
                                    placeholder="Enter your email address"
                                    class="form-input @error('email') error @enderror"
                                >
                                @error('email')
                                    <p class="form-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="message" class="form-label">
                                    <i class="bi bi-chat-text"></i>
                                    Your Message
                                </label>
                                <textarea 
                                    id="message" 
                                    name="message" 
                                    required
                                    placeholder="Tell us how we can help you..."
                                    class="form-textarea @error('message') error @enderror"
                                >{{ old('message') }}</textarea>
                                @error('message')
                                    <p class="form-error">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <button type="submit" class="btn-submit">
                                <span>Send Message</span>
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-5">
                    <div class="contact-info-section">
                        <div class="info-header">
                            <h2 class="info-title text-white">Contact Information</h2>
                            <p class="info-description text-white">
                                Have questions or need assistance? Our team is here to help! 
                                Reach out to us through any of the channels below.
                            </p>
                        </div>

                        <div class="info-cards">
                            <div class="info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-envelope-fill"></i>
                                </div>
                                <div class="info-card-content">
                                    <h3 class="info-card-title">Email Us</h3>
                                    <p class="info-card-text">info@salonbooking.com</p>
                                    <p class="info-card-text">support@salonbooking.com</p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-telephone-fill"></i>
                                </div>
                                <div class="info-card-content">
                                    <h3 class="info-card-title">Call Us</h3>
                                    <p class="info-card-text">+880 1XXX-XXXXXX</p>
                                    <p class="info-card-text">Available 9:00 AM - 6:00 PM</p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-geo-alt-fill"></i>
                                </div>
                                <div class="info-card-content">
                                    <h3 class="info-card-title">Visit Us</h3>
                                    <p class="info-card-text">123 Main Street</p>
                                    <p class="info-card-text">Dhaka, Bangladesh</p>
                                </div>
                            </div>

                            <div class="info-card">
                                <div class="info-card-icon">
                                    <i class="bi bi-clock-fill"></i>
                                </div>
                                <div class="info-card-content">
                                    <h3 class="info-card-title">Business Hours</h3>
                                    <p class="info-card-text">Monday - Saturday: 9:00 AM - 6:00 PM</p>
                                    <p class="info-card-text">Sunday: Closed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Section -->
            <div class="map-section">
                <div class="map-card">
                    <div class="map-header">
                        <i class="bi bi-map"></i>
                        <h3>Find Us on Map</h3>
                    </div>
                    <div class="map-placeholder">
                        <i class="bi bi-geo-alt" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
