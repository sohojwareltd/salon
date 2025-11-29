@extends('layouts.app')

@section('content')
<style>
    .about-salon-section {
        padding: 80px 0;
        background: #ffffff;
    }

    .about-content h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #872341;
        margin-bottom: 1.5rem;
    }

    .about-content p {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #4a5568;
        margin-bottom: 2rem;
    }

    .about-description {
        font-size: 1.15rem;
        line-height: 1.8;
        color: #4a5568;
        margin-bottom: 2rem;
    }

    .about-description h2,
    .about-description h3 {
        color: #872341;
        margin-top: 1rem;
        margin-bottom: 0.75rem;
    }

    .about-description ul,
    .about-description ol {
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }

    .about-description strong {
        color: #2d3748;
        font-weight: 600;
    }

    .about-description a {
        color: #872341;
        text-decoration: underline;
    }

    .about-content .btn {
        padding: 12px 32px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
    }

    .about-content .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(135, 35, 65, 0.3);
    }

    .about-image {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .about-image img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.3s ease;
    }

    .about-image:hover img {
        transform: scale(1.05);
    }

    .about-image::before {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 150px;
        height: 150px;
        background: linear-gradient(135deg, #872341 0%, #BE3144 100%);
        border-radius: 50%;
        z-index: -1;
        opacity: 0.1;
    }

    @media (max-width: 768px) {
        .about-salon-section {
            padding: 50px 0;
        }

        .about-content h2 {
            font-size: 2rem;
        }

        .about-content p {
            font-size: 1rem;
        }

        .about-image {
            margin-top: 40px;
        }
    }
</style>

<!-- About Salon Section -->
@if(\App\Facades\Settings::get('about_salon_title') || \App\Facades\Settings::get('about_salon_description'))
<section class="about-salon-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-12">
                <div class="about-content">
                    @if(\App\Facades\Settings::get('about_salon_title'))
                        <h2>{{ \App\Facades\Settings::get('about_salon_title') }}</h2>
                    @endif
                    
                    @if(\App\Facades\Settings::get('about_salon_description'))
                        <div class="about-description">
                            {!! \App\Facades\Settings::get('about_salon_description') !!}
                        </div>
                    @endif
                    
                    <a href="{{ route('about') }}" class="btn">
                        <i class="fas fa-arrow-right me-2"></i>Learn More About Us
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6 col-md-12">
                @if(\App\Facades\Settings::get('about_salon_image'))
                    <div class="about-image">
                        <img src="{{ asset('storage/' . \App\Facades\Settings::get('about_salon_image')) }}" 
                             alt="{{ \App\Facades\Settings::get('about_salon_title', 'About Us') }}">
                    </div>
                @else
                    <div class="about-image">
                        <img src="https://placehold.co/600x400/872341/ffffff?text=About+Our+Salon" 
                             alt="About Our Salon">
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

<!-- Rest of homepage content -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
