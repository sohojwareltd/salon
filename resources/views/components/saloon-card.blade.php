@props(['salon'])

<div class="saloon-card">
    <div class="saloon-card-image">
        @if($salon->image)
            <img src="{{ asset('storage/' . $salon->image) }}" alt="{{ $salon->name }}">
        @else
            <div class="saloon-card-placeholder">
                <i class="bi bi-building"></i>
            </div>
        @endif
        <div class="saloon-card-badge">
            @if($salon->is_featured ?? false)
                <span class="badge bg-warning">
                    <i class="bi bi-star-fill"></i> Featured
                </span>
            @endif
        </div>
    </div>
    
    <div class="saloon-card-body">
        <h5 class="saloon-card-title">{{ $salon->name }}</h5>
        
        <div class="saloon-card-rating">
            @if($salon->average_rating ?? 0 > 0)
                <div class="d-flex align-items-center">
                    <div class="stars me-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($salon->average_rating))
                                <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i - 0.5 <= $salon->average_rating)
                                <i class="bi bi-star-half text-warning"></i>
                            @else
                                <i class="bi bi-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-text">{{ number_format($salon->average_rating, 1) }}</span>
                    <span class="text-muted ms-1">({{ $salon->total_reviews ?? 0 }})</span>
                </div>
            @else
                <span class="text-muted">No reviews yet</span>
            @endif
        </div>
        
        <div class="saloon-card-info">
            <div class="info-item">
                <i class="bi bi-geo-alt-fill"></i>
                <span>{{ $salon->location ?? $salon->address }}</span>
            </div>
            @if($salon->phone)
                <div class="info-item">
                    <i class="bi bi-telephone-fill"></i>
                    <span>{{ $salon->phone }}</span>
                </div>
            @endif
        </div>
        
        @if($salon->services_count ?? 0 > 0)
            <div class="saloon-card-services">
                <i class="bi bi-scissors me-1"></i>
                <span>{{ $salon->services_count }} Services Available</span>
            </div>
        @endif
        
        <div class="saloon-card-actions">
            @if($salon->hasSubdomain())
            <a href="{{ $salon->subdomain_url }}" class="btn btn-primary w-100" target="_blank">
                <i class="bi bi-eye me-2"></i> View Details
            </a>
        </div>
    </div>
</div>

<style>
.saloon-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.saloon-card:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-8px);
}

.saloon-card-image {
    position: relative;
    width: 100%;
    height: 220px;
    overflow: hidden;
    background: linear-gradient(135deg, var(--light-gray) 0%, var(--gray) 100%);
}

.saloon-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform var(--transition-slow);
}

.saloon-card:hover .saloon-card-image img {
    transform: scale(1.1);
}

.saloon-card-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: var(--gray);
}

.saloon-card-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 2;
}

.saloon-card-body {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.saloon-card-title {
    color: var(--primary);
    font-size: 1.25rem;
    font-weight: var(--font-weight-bold);
    margin-bottom: 0.75rem;
    line-height: 1.3;
}

.saloon-card-rating {
    margin-bottom: 1rem;
}

.rating-text {
    font-weight: var(--font-weight-semibold);
    color: var(--primary);
}

.saloon-card-info {
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    color: var(--dark-gray);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.info-item i {
    color: var(--accent);
    margin-right: 0.5rem;
    font-size: 1rem;
}

.saloon-card-services {
    background-color: var(--body-bg);
    padding: 0.75rem;
    border-radius: var(--radius-md);
    margin-bottom: 1rem;
    text-align: center;
    color: var(--primary);
    font-weight: var(--font-weight-medium);
}

.saloon-card-actions {
    margin-top: auto;
}

.saloon-card-actions .btn {
    transition: all var(--transition-base);
}

.saloon-card-actions .btn:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .saloon-card-image {
        height: 180px;
    }
    
    .saloon-card-body {
        padding: 1rem;
    }
}
</style>
