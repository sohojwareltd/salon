@extends('layouts.subdomain')

@section('title', $provider->user->name . ' - Professional Barber')

@push('styles')
<style>
    /* Body & Section Background */
    body {
        background: #FFFFFF;
    }
    
    .section-light {
        background: #F9FAFB;
        padding: 3rem 0;
    }
    
    /* Provider Hero Section */
    .provider-hero {
        background: linear-gradient(135deg, #09122C 0%, #872341 50%, #BE3144 100%);
        padding: 4rem 0 8rem;
        position: relative;
        overflow: hidden;
    }
    
    .provider-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
        opacity: 0.5;
    }
    
    .provider-profile-card {
        position: relative;
        z-index: 10;
        background: #FFFFFF;
        border-radius: 1.5rem;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        padding: 3rem;
        margin-top: -5rem;
        animation: fadeInUp 0.8s ease-out;
    }
    
    .provider-profile-header {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 2rem;
    }
    
    .provider-avatar-wrapper {
        position: relative;
        flex-shrink: 0;
    }
    
    .provider-avatar {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 6px solid #FFFFFF;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        object-fit: cover;
        animation: fadeIn 0.8s ease-out 0.2s both;
    }
    
    .provider-avatar-placeholder {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        border: 6px solid #FFFFFF;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #BE3144 0%, #E17564 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        font-weight: 700;
        color: #FFFFFF;
        font-family: 'Cormorant Garamond', serif;
        animation: fadeIn 0.8s ease-out 0.2s both;
    }
    
    .provider-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        color: #FFFFFF;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        border: 4px solid #FFFFFF;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .provider-info {
        flex: 1;
        animation: fadeInUp 0.8s ease-out 0.3s both;
    }
    
    .provider-name {
        font-size: 2.5rem;
        font-weight: 700;
        font-family: 'Cormorant Garamond', serif;
        color: #09122C;
        margin-bottom: 0.5rem;
    }
    
    .provider-expertise {
        font-size: 1.25rem;
        color: #4B5563;
        margin-bottom: 1rem;
        font-weight: 500;
    }
    
    .provider-meta {
        display: flex;
        align-items: center;
        gap: 2rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    
    .provider-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .provider-rating i {
        color: #F59E0B;
        font-size: 1.25rem;
    }
    
    .provider-rating-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #09122C;
    }
    
    .provider-rating-count {
        color: #6B7280;
    }
    
    .provider-salon-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #4B5563;
        text-decoration: none;
        transition: color 0.3s ease;
    }
    
    .provider-salon-link:hover {
        color: #BE3144;
    }
    
    .provider-salon-link i {
        color: #BE3144;
        font-size: 1.125rem;
    }
    
    .btn-book-appointment {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2.5rem;
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        color: #FFFFFF;
        border: none;
        border-radius: 1rem;
        font-size: 1.125rem;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(190, 49, 68, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }
    
    .btn-book-appointment:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(190, 49, 68, 0.4);
        color: #FFFFFF;
    }
    
    @keyframes pulse {
        0%, 100% { box-shadow: 0 4px 16px rgba(190, 49, 68, 0.3); }
        50% { box-shadow: 0 4px 24px rgba(190, 49, 68, 0.5); }
    }
    
    /* Stats Section */
    .provider-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1.5rem;
        padding: 2rem;
        background: linear-gradient(135deg, #09122C 0%, #872341 100%);
        border-radius: 1rem;
        margin: 2rem 0;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
    }
    
    .provider-stat {
        text-align: center;
        padding: 1rem;
        transition: transform 0.3s ease;
    }
    
    .provider-stat:hover {
        transform: translateY(-5px);
    }
    
    .provider-stat-number {
        font-size: 2rem;
        font-weight: 700;
        font-family: 'Cormorant Garamond', serif;
        color: #FFFFFF;
        margin-bottom: 0.5rem;
    }
    
    .provider-stat-label {
        color: #D1D5DB;
        font-size: 0.9375rem;
    }
    
    /* About Section */
    .about-section {
        background: #FFFFFF;
        border-radius: 1rem;
        padding: 2.5rem;
        margin-bottom: 3rem;
        border: 2px solid #F3F4F6;
        animation: fadeInUp 0.8s ease-out 0.4s both;
        transition: all 0.3s ease;
    }
    
    .about-section:hover {
        border-color: #BE3144;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transform: translateY(-4px);
    }
    
    .section-title-icon {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 2rem;
        font-weight: 700;
        font-family: 'Cormorant Garamond', serif;
        color: #09122C;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }
    
    .section-title-icon::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 4px;
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        border-radius: 2px;
    }
    
    .section-title-icon i {
        color: #BE3144;
        font-size: 1.75rem;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-6px); }
    }
    
    .about-text {
        font-size: 1.0625rem;
        line-height: 1.8;
        color: #374151;
    }
    
    /* Services Grid */
    .services-section {
        animation: fadeInUp 0.8s ease-out 0.5s both;
        margin-bottom: 3rem;
    }
    
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .service-card {
        background: #FFFFFF;
        border-radius: 1.5rem;
        padding: 2rem;
        border: 2px solid #F3F4F6;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .service-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .service-card:hover::before {
        transform: scaleX(1);
    }
    
    .service-card:hover {
        background: linear-gradient(135deg, #09122C 0%, #872341 100%);
        border-color: #BE3144;
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }
    
    .service-card:hover .service-name {
        color: #FFFFFF;
    }
    
    .service-card:hover .service-description {
        color: #D1D5DB;
    }
    
    .service-card:hover .service-duration {
        background: rgba(255, 255, 255, 0.1);
        color: #FFFFFF;
    }
    
    .service-icon {
        width: 70px;
        height: 70px;
        border-radius: 1rem;
        background: linear-gradient(135deg, #BE3144 0%, #E17564 100%);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 1.5rem;
        transition: all 0.4s ease;
        box-shadow: 0 4px 16px rgba(225, 117, 100, 0.3);
    }
    
    .service-card:hover .service-icon {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 8px 24px rgba(225, 117, 100, 0.5);
    }
    
    .service-name {
        font-size: 1.375rem;
        font-weight: 700;
        font-family: 'Cormorant Garamond', serif;
        color: #09122C;
        margin-bottom: 0.75rem;
        transition: color 0.3s ease;
    }
    
    .service-price {
        font-size: 1.75rem;
        font-weight: 700;
        font-family: 'Cormorant Garamond', serif;
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 1rem;
    }
    
    .service-duration {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #F9FAFB;
        border-radius: 9999px;
        font-size: 0.875rem;
        color: #4B5563;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .service-description {
        color: #4B5563;
        line-height: 1.7;
        margin-bottom: 1.5rem;
        font-size: 0.9375rem;
        transition: color 0.3s ease;
    }
    
    .btn-service {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 1rem 1.5rem;
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        color: #FFFFFF;
        text-decoration: none;
        border-radius: 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(190, 49, 68, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .btn-service::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        transform: translate(-50%, -50%);
        transition: width 0.5s, height 0.5s;
    }
    
    .btn-service:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .btn-service:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(190, 49, 68, 0.4);
        color: #FFFFFF;
    }
    
    .btn-service i {
        transition: transform 0.3s ease;
    }
    
    .btn-service:hover i {
        transform: translateX(4px);
    }
    
    /* Reviews Section */
    .reviews-section {
        animation: fadeInUp 0.8s ease-out 0.6s both;
    }
    
    .reviews-grid {
        display: grid;
        gap: 2rem;
        margin-top: 2rem;
    }
    
    .review-card {
        background: #FFFFFF;
        border-radius: 1rem;
        padding: 2rem;
        border: 2px solid #F3F4F6;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .review-card::after {
        content: '\f10d';
        font-family: 'Bootstrap Icons';
        position: absolute;
        bottom: -10px;
        right: -10px;
        font-size: 6rem;
        color: #F3F4F6;
        opacity: 0.3;
        transition: all 0.4s ease;
    }
    
    .review-card:hover::after {
        color: #BE3144;
        opacity: 0.15;
        transform: scale(1.2) rotate(-10deg);
    }
    
    .review-card:hover {
        background: linear-gradient(135deg, #09122C 0%, #872341 100%);
        border-color: #BE3144;
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
    }
    
    .review-card:hover .review-author-name {
        color: #FFFFFF;
    }
    
    .review-card:hover .review-date {
        color: #D1D5DB;
    }
    
    .review-card:hover .review-text {
        color: #E5E7EB;
    }
    
    .review-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .review-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #BE3144 0%, #E17564 100%);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.125rem;
        flex-shrink: 0;
    }
    
    .review-author {
        flex: 1;
    }
    
    .review-author-name {
        font-weight: 600;
        color: #09122C;
        margin-bottom: 0.25rem;
        transition: color 0.3s ease;
    }
    
    .review-date {
        font-size: 0.875rem;
        color: #6B7280;
        transition: color 0.3s ease;
    }
    
    .review-rating {
        display: flex;
        gap: 0.25rem;
        margin-bottom: 0.75rem;
    }
    
    .review-rating i {
        color: #F59E0B;
        font-size: 1rem;
    }
    
    .review-text {
        color: #374151;
        line-height: 1.7;
        transition: color 0.3s ease;
    }
    
    /* Sidebar */
    .provider-sidebar {
        position: sticky;
        top: 6rem;
        animation: fadeInUp 0.8s ease-out 0.7s both;
    }
    
    .sidebar-card {
        background: #FFFFFF;
        border-radius: 1rem;
        padding: 2rem;
        border: 2px solid #F3F4F6;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }
    
    .sidebar-card:hover {
        border-color: #BE3144;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        transform: translateY(-4px);
    }
    
    .sidebar-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #09122C;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .sidebar-title i {
        color: #BE3144;
    }
    
    .contact-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: #F9FAFB;
        border-radius: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .contact-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: linear-gradient(135deg, #BE3144 0%, #E17564 100%);
        color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    .contact-info {
        flex: 1;
    }
    
    .contact-label {
        font-size: 0.875rem;
        color: #6B7280;
        margin-bottom: 0.25rem;
    }
    
    .contact-value {
        font-weight: 600;
        color: #09122C;
    }
    
    .availability-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .availability-badge.available {
        background: rgba(34, 197, 94, 0.1);
        color: #10B981;
    }
    
    .availability-badge.unavailable {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }
    
    .salon-card-link {
        display: block;
        padding: 1.5rem;
        background: #F9FAFB;
        border-radius: 0.75rem;
        border: 2px solid #F3F4F6;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    
    .salon-card-link:hover {
        border-color: #BE3144;
        background: #FFFFFF;
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .salon-name {
        font-weight: 700;
        font-size: 1.125rem;
        color: #BE3144;
        margin-bottom: 0.5rem;
    }
    
    .salon-address {
        color: #4B5563;
        font-size: 0.9375rem;
        line-height: 1.6;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        background: #F9FAFB;
        border-radius: 1rem;
    }
    
    .empty-icon {
        font-size: 4rem;
        color: #9CA3AF;
        margin-bottom: 1rem;
    }
    
    .empty-text {
        color: #4B5563;
        font-size: 1.125rem;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
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
    
    /* Responsive */
    @media (max-width: 768px) {
        .provider-profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .provider-name {
            font-size: 2rem;
        }
        
        .provider-avatar,
        .provider-avatar-placeholder {
            width: 140px;
            height: 140px;
        }
        
        .provider-meta {
            justify-content: center;
        }
        
        .services-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<!-- Provider Hero -->
<section class="provider-hero">
    <div class="container"></div>
</section>

<!-- Profile Card Section -->
<section class="section-light" style="padding-top: 0;">
    <div class="container">
        <div class="provider-profile-card">
            <div class="provider-profile-header">
                <!-- Avatar -->
                <div class="provider-avatar-wrapper">
                    @if($provider->photo ?? false)
                        <img src="{{ asset('storage/' . $provider->photo) }}" 
                             alt="{{ $provider->user->name }}" 
                             class="provider-avatar">

                             
                    @else
                        <div class="provider-avatar-placeholder">
                            {{ strtoupper(substr($provider->user?->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="provider-badge">
                        <i class="bi bi-scissors"></i>
                    </div>
                </div>
                
                <!-- Provider Info -->
                <div class="provider-info">
                    <h1 class="provider-name">{{ $provider->user->name }}</h1>
                    <p class="provider-expertise">
                        <i class="bi bi-award-fill" style="color: var(--primary-2);"></i>
                        Professional Barber & Stylist
                    </p>
                    
                    <div class="provider-meta">
                        <div class="provider-rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star-fill"></i>
                            @endfor
                            <span class="provider-rating-value">4.9</span>
                            <span class="provider-rating-count">({{ $provider->reviews->count() }} reviews)</span>
                        </div>
                        
                        @if($provider->salon->hasSubdomain())
                            <a href="{{ $provider->salon->subdomain_url }}" class="provider-salon-link" target="_blank">
                                <i class="bi bi-building"></i>
                                <span>{{ $provider->salon->name }}</span>
                            </a>
                        @else
                            <div class="provider-salon-link" style="cursor: default;">
                                <i class="bi bi-building"></i>
                                <span>{{ $provider->salon->name }}</span>
                            </div>
                        @endif
                        
                        <div style="color: var(--gray-600);">
                            <i class="bi bi-geo-alt-fill" style="color: var(--primary-2);"></i>
                            {{ $provider->salon->city }}, {{ $provider->salon->state }}
                        </div>
                    </div>
                    
                    @auth
                        <a href="{{ route('appointments.book', $provider) }}" class="btn-book-appointment">
                            <i class="bi bi-calendar-check"></i>
                            Book Appointment Now
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn-book-appointment">
                            <i class="bi bi-box-arrow-in-right"></i>
                            Login to Book Appointment
                        </a>
                    @endauth
                </div>
            </div>
            
            <!-- Stats -->
            <div class="provider-stats">
                <div class="provider-stat">
                    <div class="provider-stat-number">{{ $provider->appointments->count() }}+</div>
                    <div class="provider-stat-label">Happy Clients</div>
                </div>
                <div class="provider-stat">
                    <div class="provider-stat-number">{{ $provider->services->count() }}</div>
                    <div class="provider-stat-label">Services Offered</div>
                </div>
                <div class="provider-stat">
                    <div class="provider-stat-number">5+</div>
                    <div class="provider-stat-label">Years Experience</div>
                </div>
                <div class="provider-stat">
                    <div class="provider-stat-number">4.9</div>
                    <div class="provider-stat-label">Average Rating</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="section-light">
    <div class="container">
        <div class="row">
            <!-- Left Column - About, Services, Reviews -->
            <div class="col-lg-8 my-5">
                <!-- About Section -->
                <div class="about-section">
                    <h2 class="section-title-icon">
                        <i class="bi bi-person-circle"></i>
                        About {{ $provider->user->name }}
                    </h2>
                    <p class="about-text">
                        {{ $provider->bio ?? 'Professional barber with years of experience in men\'s grooming. Specializing in modern haircuts, traditional barbering techniques, and premium styling services. Committed to delivering exceptional service and ensuring every client leaves looking and feeling their best.' }}
                    </p>
                </div>
                
                <!-- Services Section -->
                <div class="services-section">
                    <h2 class="section-title-icon">
                        <i class="bi bi-scissors"></i>
                        Services Offered ({{ $provider->services->count() }})
                    </h2>
                    
                    <div class="services-grid">
                        @foreach($provider->services as $index => $service)
                            <div class="service-card" style="animation-delay: {{ 0.1 * ($index + 1) }}s;">
                                <div class="service-icon">
                                    <i class="bi bi-scissors"></i>
                                </div>
                                <h3 class="service-name">{{ $service->name }}</h3>
                                <p class="service-price">{{ Settings::formatPrice($service->price, false) }}</p>
                                <div class="service-duration">
                                    <i class="bi bi-clock"></i>
                                    <span>{{ $service->duration ?? '30' }} mins</span>
                                </div>
                                <p class="service-description">
                                    {{ $service->description ?? 'Professional service delivered with expertise and attention to detail. Get the perfect look with our skilled barbers.' }}
                                </p>
                                @auth
                                    <a href="{{ route('appointments.book', $provider) }}" class="btn-service">
                                        Book Now <i class="bi bi-arrow-right"></i>
                                    </a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-service">
                                        Login to Book <i class="bi bi-arrow-right"></i>
                                    </a>
                                @endauth
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Reviews Section -->
                <div class="reviews-section" style="margin-top: 3rem;">
                    <h2 class="section-title-icon">
                        <i class="bi bi-star"></i>
                        Customer Reviews ({{ $provider->reviews->count() }})
                    </h2>
                    
                    @if($provider->reviews->count() > 0)
                        <div class="reviews-grid">
                            @foreach($provider->reviews as $review)
                                <div class="review-card">
                                    <div class="review-header">
                                        <div class="review-avatar">
                                            {{ strtoupper(substr($review->user->name, 0, 2)) }}
                                        </div>
                                        <div class="review-author">
                                            <div class="review-author-name">{{ $review->user->name }}</div>
                                            <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="review-rating">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star-fill {{ $i <= $review->rating ? '' : 'text-gray-300' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="review-text">"{{ $review->comment }}"</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-chat-quote"></i>
                            </div>
                            <p class="empty-text">No reviews yet. Be the first to share your experience!</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Right Column - Sidebar -->
            <div class="col-lg-4 my-5">
                <div class="provider-sidebar">
                    <!-- Contact Card -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">
                            <i class="bi bi-telephone"></i>
                            Contact Details
                        </h3>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div class="contact-info">
                                <div class="contact-label">Email Address</div>
                                <div class="contact-value">{{ $provider->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                        
                        <div class="contact-item">
                            <div class="contact-icon">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div class="contact-info">
                                <div class="contact-label">Phone Number</div>
                                <div class="contact-value">{{ $provider->phone ?? $provider->salon->phone }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Availability Card -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">
                            <i class="bi bi-clock"></i>
                            Availability
                        </h3>
                        
                        @if($provider->is_active)
                            <div class="availability-badge available">
                                <i class="bi bi-check-circle-fill"></i>
                                Currently Available for Booking
                            </div>
                        @else
                            <div class="availability-badge unavailable">
                                <i class="bi bi-x-circle-fill"></i>
                                Currently Unavailable
                            </div>
                        @endif
                        
                        @if($provider->break_start && $provider->break_end)
                            <div style="padding: 1rem; background: var(--gray-50); border-radius: var(--radius-lg); margin-top: 1rem;">
                                <div style="font-weight: 600; color: var(--primary-dark); margin-bottom: 0.5rem;">
                                    <i class="bi bi-cup-hot"></i> Break Time
                                </div>
                                <div style="color: var(--gray-600);">
                                    {{ \Carbon\Carbon::parse($provider->break_start)->format('g:i A') }} - 
                                    {{ \Carbon\Carbon::parse($provider->break_end)->format('g:i A') }}
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Salon Location Card -->
                    <div class="sidebar-card">
                        <h3 class="sidebar-title">
                            <i class="bi bi-building"></i>
                            Salon Location
                        </h3>
                        
                        @if($provider->salon->hasSubdomain())
                            <a href="{{ $provider->salon->subdomain_url }}" class="salon-card-link" target="_blank">
                                <div class="salon-name">
                                    <i class="bi bi-shop"></i> {{ $provider->salon->name }}
                                </div>
                                <div class="salon-address">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    {{ $provider->salon->address }}<br>
                                    {{ $provider->salon->city }}, {{ $provider->salon->state }}
                                </div>
                            </a>
                        @else
                            <div class="salon-card-link">
                                <div class="salon-name">
                                    <i class="bi bi-shop"></i> {{ $provider->salon->name }}
                                </div>
                                <div class="salon-address">
                                    <i class="bi bi-geo-alt-fill"></i>
                                    {{ $provider->salon->address }}<br>
                                    {{ $provider->salon->city }}, {{ $provider->salon->state }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
