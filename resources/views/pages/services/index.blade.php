@extends('layouts.app')

@section('title', 'Our Services - Premium Salon Services')

@push('styles')
<style>
    /* ========================================
       HERO SECTION
    ======================================== */
    .services-hero {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-1) 50%, var(--primary-2) 100%);
        padding: 5rem 0 8rem;
        position: relative;
        overflow: hidden;
    }
    
    .services-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
        opacity: 0.5;
    }
    
    .services-hero-content {
        position: relative;
        z-index: 2;
        text-align: center;
        color: var(--white);
    }
    
    .services-hero-icon {
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
    
    .services-hero-title {
        font-size: 3.5rem;
        font-weight: 700;
        font-family: var(--font-heading);
        margin-bottom: 1rem;
        text-shadow: 2px 4px 12px rgba(0, 0, 0, 0.3);
        animation: fadeInUp 0.8s ease-out;
    }
    
    .services-hero-subtitle {
        font-size: 1.375rem;
        opacity: 0.95;
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    /* ========================================
       FILTER SECTION
    ======================================== */
    .filter-section {
        margin-top: -4rem;
        position: relative;
        z-index: 10;
        margin-bottom: 4rem;
    }

    .filter-card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        padding: 2rem;
        box-shadow: var(--shadow-2xl);
        border: 2px solid var(--gray-100);
        animation: fadeInUp 0.8s ease-out 0.3s both;
    }

    .filter-tabs {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filter-tab {
        padding: 0.75rem 2rem;
        border-radius: var(--radius-xl);
        background: var(--gray-100);
        color: var(--gray-600);
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .filter-tab:hover {
        background: var(--gray-200);
        transform: translateY(-2px);
    }

    .filter-tab.active {
        background: var(--gradient-primary);
        color: var(--white);
        border-color: var(--primary-2);
        box-shadow: 0 4px 16px rgba(190, 49, 68, 0.3);
    }

    /* ========================================
       SERVICE CARDS
    ======================================== */
    .services-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 2rem;
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    .service-card {
        background: var(--white);
        border-radius: var(--radius-2xl);
        overflow: hidden;
        border: 2px solid var(--gray-100);
        transition: all 0.3s ease;
        position: relative;
    }

    .service-card::before {
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

    .service-card:hover::before {
        transform: scaleX(1);
    }

    .service-card:hover {
        border-color: var(--primary-2);
        box-shadow: var(--shadow-2xl);
        transform: translateY(-8px);
    }

    .service-image {
        width: 100%;
        height: 240px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .service-card:hover .service-image {
        transform: scale(1.1);
    }

    .service-image-wrapper {
        overflow: hidden;
        position: relative;
        background: var(--gray-100);
    }

    .service-category-badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--primary-2);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .service-content {
        padding: 2rem;
    }

    .service-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 1rem;
    }

    .service-title {
        font-size: 1.5rem;
        font-weight: 700;
        font-family: var(--font-heading);
        color: var(--primary-dark);
        margin: 0;
        flex: 1;
    }

    .service-price {
        font-size: 1.75rem;
        font-weight: 700;
        color: var(--primary-2);
        font-family: var(--font-heading);
    }

    .service-description {
        color: var(--gray-600);
        line-height: 1.6;
        margin-bottom: 1.5rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .service-meta {
        display: flex;
        gap: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid var(--gray-100);
        margin-bottom: 1.5rem;
    }

    .service-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--gray-600);
        font-size: 0.9375rem;
    }

    .service-meta-item i {
        color: var(--primary-2);
        font-size: 1.125rem;
    }

    .service-providers {
        margin-bottom: 1.5rem;
    }

    .service-providers-label {
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--gray-500);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .service-providers-label i {
        color: var(--primary-2);
    }

    .providers-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .provider-tag {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: var(--gray-100);
        border-radius: var(--radius-lg);
        font-size: 0.875rem;
        color: var(--gray-700);
        transition: all 0.3s ease;
    }

    .provider-tag:hover {
        background: var(--primary-2);
        color: var(--white);
        transform: translateY(-2px);
    }

    .provider-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--gradient-coral);
        color: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .btn-book-service {
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
    }

    .btn-book-service::before {
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

    .btn-book-service:hover::before {
        width: 400px;
        height: 400px;
    }

    .btn-book-service:hover {
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
        .services-hero {
            padding: 4rem 0 6rem;
        }
        
        .services-hero-title {
            font-size: 2.5rem;
        }
        
        .services-hero-icon {
            width: 80px;
            height: 80px;
            font-size: 2.5rem;
        }

        .services-grid {
            grid-template-columns: 1fr;
        }

        .filter-tabs {
            justify-content: flex-start;
        }

        .filter-tab {
            padding: 0.625rem 1.5rem;
            font-size: 0.9375rem;
        }

        .service-header {
            flex-direction: column;
            gap: 0.75rem;
        }

        .service-meta {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="services-hero">
    <div class="services-hero-content">
        <div class="container">
            <div class="services-hero-icon">
                <i class="bi bi-scissors"></i>
            </div>
            <h1 class="services-hero-title">Our Premium Services</h1>
            <p class="services-hero-subtitle">Discover our wide range of professional salon services</p>
        </div>
    </div>
</section>

<!-- Filter Section -->
<section class="section-light" style="padding-top: 0;">
    <div class="container">
        <div class="filter-section">
            <div class="filter-card">
                <div class="filter-tabs">
                    <button class="filter-tab active" data-category="all">All Services</button>
                    <button class="filter-tab" data-category="haircut">Haircut</button>
                    <button class="filter-tab" data-category="styling">Styling</button>
                    <button class="filter-tab" data-category="coloring">Coloring</button>
                    <button class="filter-tab" data-category="beard">Beard</button>
                    <button class="filter-tab" data-category="spa">Spa & Massage</button>
                    <button class="filter-tab" data-category="makeup">Makeup</button>
                </div>
            </div>
        </div>

        <!-- Services Grid -->
        <div class="services-grid">
            @forelse($services as $service)
                <div class="service-card" data-category="{{ strtolower($service->category) }}">
                    <div class="service-image-wrapper">
                        @if($service->image)
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="service-image">
                        @else
                            <img src="https://images.unsplash.com/photo-1562322140-8baeececf3df?w=800" alt="{{ $service->name }}" class="service-image">
                        @endif
                        <div class="service-category-badge">
                            {{ $service->category }}
                        </div>
                    </div>

                    <div class="service-content">
                        <div class="service-header">
                            <h3 class="service-title">{{ $service->name }}</h3>
                            <div class="service-price">{{ Settings::formatPrice($service->price, false) }}</div>
                        </div>

                        <p class="service-description">{{ $service->description }}</p>

                        <div class="service-meta">
                            <div class="service-meta-item">
                                <i class="bi bi-clock"></i>
                                <span>{{ $service->duration }} mins</span>
                            </div>
                            <div class="service-meta-item">
                                <i class="bi bi-people"></i>
                                <span>{{ $service->providers->count() }} Providers</span>
                            </div>
                        </div>

                        @if($service->providers->count() > 0)
                            <div class="service-providers">
                                <div class="service-providers-label">
                                    <i class="bi bi-person-check"></i>
                                    Available Providers:
                                </div>
                                <div class="providers-list">
                                    @foreach($service->providers->take(3) as $provider)
                                        <a href="{{ route('providers.show', $provider) }}" class="provider-tag">
                                            <div class="provider-avatar">
                                                @if($provider->photo)
                                                    <img src="{{ asset('storage/' . $provider->photo) }}" alt="{{ $provider->name }}" style="width: 100%; height: 100%; border-radius: 50%; object-fit: cover;">
                                                @else
                                                    {{ strtoupper(substr($provider->name, 0, 2)) }}
                                                @endif
                                            </div>
                                            <span>{{ $provider->name }}</span>
                                        </a>
                                    @endforeach
                                    @if($service->providers->count() > 3)
                                        <div class="provider-tag" style="opacity: 0.6; cursor: default;">
                                            <div class="provider-avatar" style="background: var(--gray-200);">
                                                +
                                            </div>
                                            <span>+{{ $service->providers->count() - 3 }} more</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($service->providers->count() > 0)
                            <a href="{{ route('appointments.book', $service->providers->first()) }}?service={{ $service->id }}" class="btn-book-service" style="text-decoration: none;">
                                <span>Book Now</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        @else
                            <button class="btn-book-service" style="opacity: 0.6; cursor: not-allowed;" disabled>
                                <span>No Providers Available</span>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-state-icon">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h2 class="empty-state-title">No Services Found</h2>
                    <p class="empty-state-text">We're currently updating our services. Please check back soon!</p>
                </div>
            @endforelse
        </div>

        @if($services->hasPages())
            <div class="pagination-wrapper mb-5">
                {{ $services->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Filter functionality
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            // Update active tab
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            const cards = document.querySelectorAll('.service-card');
            
            cards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush
