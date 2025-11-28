@extends('layouts.app')

@section('title', 'Our Providers - Expert Professionals')

@push('styles')
<style>
    /* ========================================
       HERO SECTION
    ======================================== */
    .providers-hero {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-1) 50%, var(--primary-2) 100%);
        padding: 5rem 0 8rem;
        position: relative;
        overflow: hidden;
    }
    
    .providers-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
        opacity: 0.5;
    }
    
    .providers-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: var(--white);
    }
    
    .providers-hero-icon {
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
    
    .providers-hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        font-family: var(--font-heading);
        margin-bottom: 1rem;
        text-shadow: 2px 4px 12px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease-out;
    }
    
    .providers-hero-subtitle {
        font-size: 1.375rem;
        opacity: 0.95;
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    /* ========================================
       SEARCH SECTION
    ======================================== */
    .search-section {
        margin-top: -4rem;
        position: relative;
        z-index: 10;
        margin-bottom: 4rem;
    }

    .search-card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        padding: 2rem;
        box-shadow: var(--shadow-2xl);
        border: 2px solid var(--gray-100);
        animation: fadeInUp 0.8s ease-out 0.3s both;
    }

    .search-box {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .search-input {
        flex: 1;
        padding: 1rem 1.5rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-xl);
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-2);
        box-shadow: 0 0 0 4px rgba(190, 49, 68, 0.1);
    }

    .btn-search {
        padding: 1rem 2rem;
        background: var(--gradient-primary);
        color: var(--white);
        border: none;
        border-radius: var(--radius-xl);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 16px rgba(190, 49, 68, 0.3);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-search:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(190, 49, 68, 0.4);
    }

    /* ========================================
       PROVIDER CARDS
    ======================================== */
    .providers-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    .provider-card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        overflow: hidden;
        border: 2px solid var(--gray-100);
        transition: all 0.3s ease;
        position: relative;
    }

    .provider-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--gradient-primary);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s ease;
    }

    .provider-card:hover::before {
        transform: scaleX(1);
    }

    .provider-card:hover {
        border-color: var(--primary-2);
        box-shadow: var(--shadow-2xl);
        transform: translateY(-8px);
    }

    .provider-header {
        padding: 2rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(9, 18, 44, 0.03) 0%, rgba(190, 49, 68, 0.03) 100%);
    }

    .provider-avatar-wrapper {
        width: 120px;
        height: 120px;
        margin: 0 auto 1.5rem;
        position: relative;
    }

    .provider-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid var(--white);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
    }

    .provider-status {
        position: absolute;
        bottom: 5px;
        right: 5px;
        width: 24px;
        height: 24px;
        background: var(--success);
        border: 3px solid var(--white);
        border-radius: 50%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .provider-status.inactive {
        background: var(--gray-400);
    }

    .provider-name {
        font-size: 1.75rem;
        font-weight: 700;
        font-family: var(--font-heading);
        color: var(--primary-dark);
        margin-bottom: 0.5rem;
    }

    .provider-salon {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        color: var(--gray-600);
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .provider-salon i {
        color: var(--primary-2);
    }

    .provider-rating {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--primary-dark);
    }

    .provider-rating i {
        color: #FFC107;
        font-size: 1.25rem;
    }

    .provider-body {
        padding: 2rem;
    }

    .provider-expertise {
        margin-bottom: 1.5rem;
    }

    .provider-expertise-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-500);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .provider-expertise-label i {
        color: var(--primary-2);
    }

    .expertise-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .expertise-tag {
        padding: 0.5rem 1rem;
        background: var(--gray-100);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        color: var(--gray-700);
        font-weight: 500;
    }

    .provider-bio {
        color: var(--gray-600);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .provider-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1rem;
        padding: 1.5rem 0;
        border-top: 2px solid var(--gray-100);
        border-bottom: 2px solid var(--gray-100);
        margin-bottom: 1.5rem;
    }

    .provider-stat {
        text-align: center;
    }

    .provider-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-2);
        font-family: var(--font-heading);
    }

    .provider-stat-label {
        font-size: 0.75rem;
        color: var(--gray-500);
        margin-top: 0.25rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .provider-reviews-preview {
        margin-bottom: 1.5rem;
    }

    .review-preview {
        background: var(--gray-50);
        border-radius: var(--radius-lg);
        padding: 1rem;
        border-left: 3px solid var(--primary-2);
    }

    .review-preview-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .review-preview-author {
        font-weight: 600;
        color: var(--primary-dark);
        font-size: 0.9375rem;
    }

    .review-preview-rating {
        color: #FFC107;
        font-size: 0.875rem;
    }

    .review-preview-text {
        color: var(--gray-600);
        font-size: 0.875rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-view-provider {
        width: 100%;
        padding: 1rem 2rem;
        background: var(--gradient-primary);
        color: var(--white);
        border: none;
        border-radius: var(--radius-xl);
        font-size: 1rem;
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
        text-decoration: none;
    }

    .btn-view-provider::before {
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

    .btn-view-provider:hover::before {
        width: 400px;
        height: 400px;
    }

    .btn-view-provider:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(190, 49, 68, 0.4);
    }

    /* ========================================
       EMPTY STATE
    ======================================== */
    .empty-state {
        text-align: center;
        padding: 5rem 2rem;
        animation: fadeInUp 0.8s ease-out;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
        background: var(--gray-100);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 4rem;
        color: var(--gray-400);
    }

    .empty-state-title {
        font-size: 2rem;
        font-weight: 700;
        font-family: var(--font-heading);
        color: var(--primary-dark);
        margin-bottom: 1rem;
    }

    .empty-state-text {
        color: var(--gray-600);
        font-size: 1.125rem;
        margin-bottom: 2rem;
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

    /* ========================================
       PAGINATION
    ======================================== */
    .pagination-wrapper {
        margin-top: 4rem;
        display: flex;
        justify-content: center;
        animation: fadeInUp 0.8s ease-out 0.6s both;
    }

    .pagination {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination .page-item {
        margin: 0;
    }

    .pagination .page-link {
        padding: 0.75rem 1.125rem;
        border: 2px solid var(--gray-200);
        border-radius: var(--radius-lg);
        color: var(--gray-700);
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        background: var(--white);
        display: flex;
        align-items: center;
        gap: 0.375rem;
        min-width: 44px;
        justify-content: center;
    }

    .pagination .page-link:hover {
        border-color: var(--primary-2);
        color: var(--primary-2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(190, 49, 68, 0.2);
    }

    .pagination .page-item.active .page-link {
        background: var(--gradient-primary);
        border-color: var(--primary-2);
        color: var(--white);
        box-shadow: 0 4px 16px rgba(190, 49, 68, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
        background: var(--gray-100);
    }

    /* Hide default Laravel text */
    .pagination .page-link svg {
        width: 16px;
        height: 16px;
    }

    /* ========================================
       RESPONSIVE DESIGN
    ======================================== */
    @media (max-width: 768px) {
        .providers-hero {
            padding: 4rem 0 6rem;
        }
        
        .providers-hero-title {
            font-size: 2.5rem;
        }
        
        .providers-hero-icon {
            width: 80px;
            height: 80px;
            font-size: 2.5rem;
        }

        .providers-grid {
            grid-template-columns: 1fr;
        }

        .search-box {
            flex-direction: column;
        }

        .btn-search {
            width: 100%;
            justify-content: center;
        }

        .provider-stats {
            gap: 0.5rem;
        }

        .provider-stat-value {
            font-size: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="providers-hero">
    <div class="providers-hero-content">
        <div class="container">
            <div class="providers-hero-icon">
                <i class="bi bi-people"></i>
            </div>
            <h1 class="providers-hero-title">Our Expert Providers</h1>
            <p class="providers-hero-subtitle">Meet our talented professionals ready to serve you</p>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="section-light my-5" style="padding-top: 0;">
    <div class="container">
        <div class="search-section">
            <div class="search-card">
                <form class="search-box" method="GET">
                    <input 
                        type="text" 
                        name="search" 
                        class="search-input" 
                        placeholder="Search providers by name, salon, or expertise..."
                        value="{{ request('search') }}"
                    >
                    <button type="submit" class="btn-search">
                        <i class="bi bi-search"></i>
                        <span>Search</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Providers Grid -->
        <div class="providers-grid">
            @forelse($providers as $provider)
                <div class="provider-card">
                    <div class="provider-header">
                        <div class="provider-avatar-wrapper">
                            @if($provider->photo)
                                <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}" class="provider-avatar">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($provider->name) }}&size=240&background=BE3144&color=fff&bold=true" alt="{{ $provider->name }}" class="provider-avatar">
                            @endif
                            <div class="provider-status {{ $provider->is_active ? '' : 'inactive' }}"></div>
                        </div>
                        
                        <h3 class="provider-name">{{ $provider->name }}</h3>

                        <div class="provider-rating">
                            <i class="bi bi-star-fill"></i>
                            <span>{{ number_format($provider->average_rating, 1) }}</span>
                            <span style="color: var(--gray-500); font-weight: 400;">({{ $provider->total_reviews }} reviews)</span>
                        </div>
                    </div>

                    <div class="provider-body">
                        @if($provider->expertise)
                            <div class="provider-expertise">
                                <div class="provider-expertise-label">
                                    <i class="bi bi-award"></i>
                                    Expertise:
                                </div>
                                <div class="expertise-tags">
                                    @foreach(explode(',', $provider->expertise) as $skill)
                                        <span class="expertise-tag">{{ trim($skill) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if($provider->bio)
                            <p class="provider-bio">{{ $provider->bio }}</p>
                        @endif

                        <div class="provider-stats">
                            <div class="provider-stat">
                                <div class="provider-stat-value">{{ $provider->services->count() }}</div>
                                <div class="provider-stat-label">Services</div>
                            </div>
                            <div class="provider-stat">
                                <div class="provider-stat-value">{{ $provider->appointments->count() }}</div>
                                <div class="provider-stat-label">Bookings</div>
                            </div>
                            <div class="provider-stat">
                                <div class="provider-stat-value">{{ $provider->total_reviews }}</div>
                                <div class="provider-stat-label">Reviews</div>
                            </div>
                        </div>

                        @if($provider->reviews->count() > 0)
                            <div class="provider-reviews-preview">
                                @php
                                    $latestReview = $provider->reviews->sortByDesc('created_at')->first();
                                @endphp
                                <div class="review-preview">
                                    <div class="review-preview-header">
                                        <span class="review-preview-author">{{ $latestReview->user->name }}</span>
                                        <span class="review-preview-rating">
                                            @for($i = 0; $i < 5; $i++)
                                                @if($i < $latestReview->rating)
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </span>
                                    </div>
                                    <p class="review-preview-text">"{{ $latestReview->comment }}"</p>
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('providers.show', $provider) }}" class="btn-view-provider">
                            <span>View Profile & Book</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">
                        <i class="bi bi-person-x"></i>
                    </div>
                    <h2 class="empty-state-title">No Providers Found</h2>
                    <p class="empty-state-text">
                        @if(request('search'))
                            No providers match your search criteria. Try a different search term.
                        @else
                            We're currently onboarding new providers. Please check back soon!
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        @if($providers->hasPages())
            <div class="pagination-wrapper mb-4">
                {{ $providers->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>
@endsection
