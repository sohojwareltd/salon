@extends('layouts.app')

@section('title', 'Home')

@push('styles')
<link href="{{ asset('css/home-sections.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Premium Hero Section -->
<div class="hero-section animate-fadeIn" style="background: linear-gradient(rgba(9, 18, 44, 0.7), rgba(135, 35, 65, 0.7)), url('{{ App\Facades\Settings::get('hero_image') ? asset('storage/' . App\Facades\Settings::get('hero_image')) : 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?w=1600' }}') center/cover no-repeat; position: relative;">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title animate-fadeInUp">
                {!! nl2br(e(App\Facades\Settings::get('hero_title', 'The Fyna Barber\'s House'))) !!}
            </h1>
            <p class="hero-subtitle animate-fadeInUp" style="animation-delay: 0.2s;">
                {{ App\Facades\Settings::get('hero_subtitle', 'Experience authentic style where tradition meets modern grooming excellence') }}
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap animate-fadeInUp" style="animation-delay: 0.4s;">
                <a href="{{ App\Facades\Settings::get('hero_button_link', route('providers.index')) }}" class="btn btn-white btn-lg">
                    <i class="bi bi-scissors"></i> {{ App\Facades\Settings::get('hero_button_text', 'Browse Salons') }}
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-outline btn-lg" style="border-color: white; color: white;">
                        <i class="bi bi-person-plus"></i> Get Started
                    </a>
                @endguest
            </div>
        </div>
    </div>
</div>

<!-- Premium Features Section -->
<section class="section-light">
    <div class="container">
        <div class="section-header animate-fadeInUp">
            <h2 class="section-title">Why Choose Us</h2>
            <p class="section-subtitle">Authentic style • Made your appointment • Get me in 5 min now</p>
        </div>
        
        <div class="row">
            @if(App\Facades\Settings::get('feature_1_title'))
            <div class="col-12 col-md-4 mb-4 animate-fadeInUp">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi {{ App\Facades\Settings::get('feature_1_icon', 'bi-calendar-check') }}"></i>
                    </div>
                    <h3 class="feature-title">{{ App\Facades\Settings::get('feature_1_title') }}</h3>
                    <p class="feature-description">{{ App\Facades\Settings::get('feature_1_description') }}</p>
                </div>
            </div>
            @endif
            
            @if(App\Facades\Settings::get('feature_2_title'))
            <div class="col-12 col-md-4 mb-4 animate-fadeInUp" style="animation-delay: 0.1s;">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi {{ App\Facades\Settings::get('feature_2_icon', 'bi-award') }}"></i>
                    </div>
                    <h3 class="feature-title">{{ App\Facades\Settings::get('feature_2_title') }}</h3>
                    <p class="feature-description">{{ App\Facades\Settings::get('feature_2_description') }}</p>
                </div>
            </div>
            @endif
            
            @if(App\Facades\Settings::get('feature_3_title'))
            <div class="col-12 col-md-4 mb-4 animate-fadeInUp" style="animation-delay: 0.2s;">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi {{ App\Facades\Settings::get('feature_3_icon', 'bi-shield-check') }}"></i>
                    </div>
                    <h3 class="feature-title">{{ App\Facades\Settings::get('feature_3_title') }}</h3>
                    <p class="feature-description">{{ App\Facades\Settings::get('feature_3_description') }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Premium Services Section -->
@if($services->count() > 0)
<section class="section-light">
    <div class="container">
        <div class="section-header animate-fadeInUp">
            <h2 class="section-title">Popular Services</h2>
            <p class="section-subtitle">Professional grooming services tailored to your style</p>
        </div>
        
        <div class="row justify-content-center">
            @foreach($services as $service)
                <div class="col-12 col-sm-6 col-lg-4 mb-4 animate-fadeInUp" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                    <div class="service-card">
                        <div class="service-icon">
                            @if($service->category === 'haircut')
                                <i class="bi bi-scissors"></i>
                            @elseif($service->category === 'shaving')
                                <i class="bi bi-emoji-smile"></i>
                            @elseif($service->category === 'beard')
                                <i class="bi bi-person"></i>
                            @elseif($service->category === 'facial')
                                <i class="bi bi-stars"></i>
                            @elseif($service->category === 'massage')
                                <i class="bi bi-hand-thumbs-up"></i>
                            @else
                                <i class="bi bi-star"></i>
                            @endif
                        </div>
                        <h3 class="service-name">{{ $service->name }}</h3>
                        <p class="service-price">{{  App\Facades\Settings::formatPrice($service->price) }}</p>
                        <p class="service-description">{{ Illuminate\Support\Str::limit($service->description, 80) }}</p>
                        <div class="service-meta">
                            <span><i class="bi bi-clock"></i> {{ $service->duration }} min</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4 animate-fadeInUp">
            <a href="{{ route('providers.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-grid"></i> View All Services
            </a>
        </div>
    </div>
</section>
@endif

<!-- Top Rated Providers Section -->
@if($topProviders->count() > 0)
<section style="background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-1) 100%); padding: 5rem 0;">
    <div class="container-fluid" style="padding: 0 3rem;">
        <div class="section-header animate-fadeInUp">
            <h2 class="section-title" style="color: white;">Top Rated Providers</h2>
            <p class="section-subtitle" style="color: rgba(255, 255, 255, 0.9);">Meet our highest-rated professionals with exceptional service</p>
        </div>
        
        <div class="providers-slider-wrapper">
            <button class="slider-nav slider-prev" onclick="slideProviders('prev')">
                <i class="bi bi-chevron-left"></i>
            </button>
            
            <div class="providers-slider" id="providersSlider">
                @foreach($topProviders as $provider)
                    <div class="provider-slide animate-fadeInUp">
                        <div class="provider-slide-card">
                            <div class="provider-slide-header">
                                @if($provider->photo)
                                    <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}" class="provider-slide-avatar">
                                @else
                                    <div class="provider-slide-avatar-placeholder">
                                        {{ strtoupper(substr($provider->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div class="provider-slide-badge">
                                    <i class="bi bi-star-fill"></i>
                                    {{ number_format($provider->average_rating, 1) }}
                                </div>
                            </div>
                            
                            <div class="provider-slide-body">
                                <h3 class="provider-slide-name">{{ $provider->name }}</h3>
                                
                                @if($provider->expertise)
                                    <p class="provider-slide-expertise">
                                        <i class="bi bi-award"></i>
                                        {{ Illuminate\Support\Str::limit($provider->expertise, 40) }}
                                    </p>
                                @endif
                                
                                <div class="provider-slide-stats">
                                    <span>
                                        <i class="bi bi-scissors"></i>
                                        {{ $provider->services->count() }} services
                                    </span>
                                    <span>
                                        <i class="bi bi-chat-quote"></i>
                                        {{ $provider->total_reviews }} reviews
                                    </span>
                                </div>
                                
                                <a href="{{ route('providers.show', $provider) }}" class="btn-provider-slide">
                                    View Profile
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <button class="slider-nav slider-next" onclick="slideProviders('next')">
                <i class="bi bi-chevron-right"></i>
            </button>
        </div>
        
        <div class="slider-dots" id="sliderDots"></div>
    </div>
</section>

<style>
    .providers-slider-wrapper {
        position: relative;
        margin: 0 auto;
        max-width: 100%;
        overflow: hidden;
    }
    
    .providers-slider {
        display: flex;
        gap: 1.5rem;
        transition: transform 0.5s ease;
        padding: 1rem 0;
    }
    
    .provider-slide {
        flex: 0 0 calc(16.666% - 1.25rem);
        min-width: calc(16.666% - 1.25rem);
    }
    
    .provider-slide-card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        overflow: hidden;
        border: 2px solid var(--gray-100);
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .provider-slide-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-2xl);
        border-color: var(--primary-2);
    }
    
    .provider-slide-header {
        position: relative;
        padding: 1.5rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(9, 18, 44, 0.03) 0%, rgba(190, 49, 68, 0.03) 100%);
    }
    
    .provider-slide-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid var(--white);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        margin: 0 auto;
    }
    
    .provider-slide-avatar-placeholder {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: var(--gradient-primary);
        border: 3px solid var(--white);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: 700;
        color: var(--white);
    }
    
    .provider-slide-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: var(--gradient-primary);
        color: var(--white);
        padding: 0.375rem 0.75rem;
        border-radius: var(--radius-lg);
        font-weight: 700;
        font-size: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        box-shadow: 0 4px 12px rgba(190, 49, 68, 0.3);
    }
    
    .provider-slide-body {
        padding: 1.25rem;
    }
    
    .provider-slide-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
        font-family: var(--font-heading);
    }
    
    .provider-slide-salon {
        display: flex;
        align-items: center;
        gap: 0.375rem;
        color: var(--gray-600);
        font-size: 0.8125rem;
        margin-bottom: 0.5rem;
    }
    
    .provider-slide-salon i {
        color: var(--primary-2);
        font-size: 0.875rem;
    }
    
    .provider-slide-expertise {
        color: var(--gray-600);
        font-size: 0.75rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: start;
        gap: 0.375rem;
        line-height: 1.4;
    }
    
    .provider-slide-expertise i {
        color: var(--primary-2);
        margin-top: 0.125rem;
        font-size: 0.75rem;
    }
    
    .provider-slide-stats {
        display: flex;
        gap: 0.75rem;
        padding: 0.75rem 0;
        margin-bottom: 0.75rem;
        border-top: 1px solid var(--gray-100);
        border-bottom: 1px solid var(--gray-100);
        font-size: 0.75rem;
        color: var(--gray-600);
    }
    
    .provider-slide-stats span {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .provider-slide-stats i {
        color: var(--primary-2);
    }
    
    .btn-provider-slide {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.375rem;
        width: 100%;
        padding: 0.625rem 1rem;
        background: var(--gradient-primary);
        color: var(--white);
        border: none;
        border-radius: var(--radius-lg);
        font-weight: 600;
        font-size: 0.8125rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(190, 49, 68, 0.3);
    }
    
    .btn-provider-slide:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(190, 49, 68, 0.4);
        color: var(--white);
    }
    
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: var(--white);
        border: 2px solid var(--gray-200);
        color: var(--primary-dark);
        font-size: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 100;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }
    
    .slider-nav:hover {
        background: var(--gradient-primary);
        color: var(--white);
        border-color: var(--primary-2);
        transform: translateY(-50%) scale(1.1);
    }
    
    .slider-prev {
        left: 0;
    }
    
    .slider-next {
        right: 0;
    }
    
    .slider-dots {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 2rem;
    }
    
    .slider-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: var(--gray-300);
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .slider-dot.active {
        background: var(--primary-2);
        transform: scale(1.3);
    }
    
    @media (max-width: 1400px) {
        .provider-slide {
            flex: 0 0 calc(25% - 1.125rem);
            min-width: calc(25% - 1.125rem);
        }
    }
    
    @media (max-width: 1200px) {
        .provider-slide {
            flex: 0 0 calc(33.333% - 1rem);
            min-width: calc(33.333% - 1rem);
        }
    }
    
    @media (max-width: 768px) {
        .provider-slide {
            flex: 0 0 calc(50% - 0.75rem);
            min-width: calc(50% - 0.75rem);
        }
    }
    
    @media (max-width: 576px) {
        .provider-slide {
            flex: 0 0 100%;
            min-width: 100%;
        }
        
        .slider-nav {
            width: 40px;
            height: 40px;
            font-size: 1.25rem;
        }
        
        .slider-prev {
            left: -15px;
        }
        
        .slider-next {
            right: -15px;
        }
    }
</style>

<script>
    let currentSlide = 0;
    let slidesPerView = 6;
    
    function updateSlidesPerView() {
        if (window.innerWidth <= 576) {
            slidesPerView = 1;
        } else if (window.innerWidth <= 768) {
            slidesPerView = 2;
        } else if (window.innerWidth <= 1200) {
            slidesPerView = 3;
        } else if (window.innerWidth <= 1400) {
            slidesPerView = 4;
        } else {
            slidesPerView = 6;
        }
    }
    
    function slideProviders(direction) {
        const slider = document.getElementById('providersSlider');
        const slides = slider.querySelectorAll('.provider-slide');
        const totalSlides = slides.length;
        const maxSlide = Math.ceil(totalSlides / slidesPerView) - 1;
        
        if (direction === 'next') {
            currentSlide = currentSlide >= maxSlide ? 0 : currentSlide + 1;
        } else {
            currentSlide = currentSlide <= 0 ? maxSlide : currentSlide - 1;
        }
        
        updateSlider();
    }
    
    function goToSlide(index) {
        currentSlide = index;
        updateSlider();
    }
    
    function updateSlider() {
        const slider = document.getElementById('providersSlider');
        const slideWidth = 100 / slidesPerView;
        const translateX = -currentSlide * 100;
        slider.style.transform = `translateX(${translateX}%)`;
        
        updateDots();
    }
    
    function updateDots() {
        const dots = document.querySelectorAll('.slider-dot');
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
    }
    
    function initDots() {
        const dotsContainer = document.getElementById('sliderDots');
        const slider = document.getElementById('providersSlider');
        const slides = slider.querySelectorAll('.provider-slide');
        const totalSlides = slides.length;
        const maxSlide = Math.ceil(totalSlides / slidesPerView);
        
        dotsContainer.innerHTML = '';
        
        for (let i = 0; i < maxSlide; i++) {
            const dot = document.createElement('div');
            dot.className = 'slider-dot';
            if (i === 0) dot.classList.add('active');
            dot.onclick = () => goToSlide(i);
            dotsContainer.appendChild(dot);
        }
    }
    
    // Auto-slide every 5 seconds
    setInterval(() => {
        slideProviders('next');
    }, 5000);
    
    // Initialize on load
    window.addEventListener('load', () => {
        updateSlidesPerView();
        initDots();
    });
    
    // Re-initialize on window resize
    window.addEventListener('resize', () => {
        updateSlidesPerView();
        currentSlide = 0;
        updateSlider();
        initDots();
    });
</script>
@endif

<!-- Premium CTA Section -->
<section class="cta-section">
    <div class="container" style="position: relative; z-index: 1;">
        <div class="row align-items-center">
            <div class="col-lg-7 text-center text-lg-start mb-4 mb-lg-0 animate-fadeInLeft">
                <div class="cta-badge">
                    <i class="bi bi-gift-fill"></i>
                    Special Weekend Offer
                </div>
                <h2 class="cta-title mb-3">{!! nl2br(e(App\Facades\Settings::get('cta_title', 'Get 20% Off Every Sunday'))) !!}</h2>
                <p style="font-size: 1.25rem; color: var(--gray-300); margin-bottom: 2rem; line-height: 1.8;">
                    {{ App\Facades\Settings::get('cta_description', 'Join thousands of satisfied customers who trust us for their grooming needs. Book now and save!') }}
                </p>
                <div class="d-flex gap-3 justify-content-center justify-content-start flex-wrap">
                    @guest
                        <a href="{{ route('register') }}" class="btn-white btn-lg">
                            <i class="bi bi-person-plus"></i> Create Free Account
                        </a>
                    @else
                        <a href="{{ App\Facades\Settings::get('cta_button_link', route('providers.index')) }}" class="btn-white btn-lg">
                            <i class="bi bi-calendar-check"></i> {{ App\Facades\Settings::get('cta_button_text', 'Book Appointment') }}
                        </a>
                    @endguest
                    <a href="{{ route('providers.index') }}" class="btn-outline btn-lg" style="border-color: white; color: white;">
                        <i class="bi bi-search"></i> Browse Salons
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center animate-fadeInRight">
                <div class="cta-discount-badge">
                    <div style="text-align: center; color: var(--white);">
                        @php
                            $discountParts = explode(' ', App\Facades\Settings::get('cta_discount_text', '20% OFF'));
                            $percentage = $discountParts[0] ?? '20%';
                            $offText = $discountParts[1] ?? 'OFF';
                        @endphp
                        <p style="font-size: 4rem; font-weight: 700; line-height: 1; margin: 0; font-family: var(--font-heading);">{{ $percentage }}</p>
                        <p style="font-size: 1.5rem; font-weight: 600; opacity: 0.95; margin: 0.5rem 0;">{{ $offText }}</p>
                        <p style="font-size: 1rem; opacity: 0.85; margin: 0;">Every Sunday</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
