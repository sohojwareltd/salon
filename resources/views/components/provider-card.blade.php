@props(['provider'])

<div class="provider-card">
    <div class="provider-card-header">
        <div class="provider-avatar">
            @if($provider->user->avatar ?? false)
                <img src="{{ asset('storage/' . $provider->user->avatar) }}" alt="{{ $provider->user->name }}">
            @else
                <div class="avatar-placeholder">
                    <i class="bi bi-person-circle"></i>
                </div>
            @endif
            @if($provider->is_available ?? true)
                <span class="availability-badge available">
                    <i class="bi bi-circle-fill"></i>
                </span>
            @else
                <span class="availability-badge unavailable">
                    <i class="bi bi-circle-fill"></i>
                </span>
            @endif
        </div>
    </div>
    
    <div class="provider-card-body">
        <h5 class="provider-name">{{ $provider->user->name }}</h5>
        
        @if($provider->specialization)
            <p class="provider-specialization">
                <i class="bi bi-award me-1"></i> {{ $provider->specialization }}
            </p>
        @endif
        
        <div class="provider-rating">
            @if(($provider->average_rating ?? 0) > 0)
                <div class="d-flex align-items-center justify-content-center">
                    <div class="stars me-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= floor($provider->average_rating))
                                <i class="bi bi-star-fill text-warning"></i>
                            @elseif($i - 0.5 <= $provider->average_rating)
                                <i class="bi bi-star-half text-warning"></i>
                            @else
                                <i class="bi bi-star text-warning"></i>
                            @endif
                        @endfor
                    </div>
                    <span class="rating-value">{{ number_format($provider->average_rating, 1) }}</span>
                </div>
                <small class="text-muted">({{ $provider->total_reviews ?? 0 }} reviews)</small>
            @else
                <span class="text-muted">New Provider</span>
            @endif
        </div>
        
        <div class="provider-stats">
            <div class="stat-item">
                <i class="bi bi-clipboard-check"></i>
                <div>
                    <div class="stat-value">{{ $provider->completed_appointments ?? 0 }}</div>
                    <div class="stat-label">Completed</div>
                </div>
            </div>
            <div class="stat-item">
                <i class="bi bi-clock-history"></i>
                <div>
                    <div class="stat-value">{{ $provider->years_experience ?? 'N/A' }}</div>
                    <div class="stat-label">Years Exp.</div>
                </div>
            </div>
        </div>
        
        @if($provider->services ?? false)
            <div class="provider-services">
                @foreach($provider->services->take(3) as $service)
                    <span class="service-tag">{{ $service->name }}</span>
                @endforeach
                @if($provider->services->count() > 3)
                    <span class="service-tag">+{{ $provider->services->count() - 3 }} more</span>
                @endif
            </div>
        @endif
        
        <div class="provider-actions">
            <a href="{{ route('providers.show', $provider) }}" class="btn btn-primary w-100">
                <i class="bi bi-calendar-plus me-2"></i> Book Now
            </a>
        </div>
    </div>
</div>

<style>
.provider-card {
    background: var(--white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    transition: all var(--transition-base);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.provider-card:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-8px);
}

.provider-card-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    padding: 2rem 1.5rem 1rem;
    display: flex;
    justify-content: center;
    position: relative;
}

.provider-avatar {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid var(--white);
    box-shadow: var(--shadow-lg);
}

.provider-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-placeholder {
    width: 100%;
    height: 100%;
    background: var(--light-gray);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    color: var(--gray);
}

.availability-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--white);
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: var(--shadow-md);
}

.availability-badge i {
    font-size: 0.7rem;
}

.availability-badge.available i {
    color: var(--success);
}

.availability-badge.unavailable i {
    color: var(--gray);
}

.provider-card-body {
    padding: 1.5rem;
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.provider-name {
    color: var(--primary);
    font-size: 1.25rem;
    font-weight: var(--font-weight-bold);
    margin-bottom: 0.5rem;
}

.provider-specialization {
    color: var(--accent);
    font-size: 0.9rem;
    font-weight: var(--font-weight-medium);
    margin-bottom: 1rem;
}

.provider-rating {
    margin-bottom: 1rem;
}

.rating-value {
    font-weight: var(--font-weight-semibold);
    color: var(--primary);
    font-size: 1rem;
}

.provider-stats {
    display: flex;
    justify-content: space-around;
    padding: 1rem 0;
    margin-bottom: 1rem;
    border-top: 1px solid var(--light-gray);
    border-bottom: 1px solid var(--light-gray);
}

.stat-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.stat-item i {
    font-size: 1.5rem;
    color: var(--accent);
}

.stat-value {
    font-size: 1.25rem;
    font-weight: var(--font-weight-bold);
    color: var(--primary);
    line-height: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: var(--gray);
}

.provider-services {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    justify-content: center;
    margin-bottom: 1rem;
}

.service-tag {
    background-color: var(--body-bg);
    color: var(--primary);
    padding: 0.375rem 0.75rem;
    border-radius: var(--radius-sm);
    font-size: 0.8rem;
    font-weight: var(--font-weight-medium);
}

.provider-actions {
    margin-top: auto;
}

.provider-actions .btn {
    transition: all var(--transition-base);
}

.provider-actions .btn:hover {
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    .provider-avatar {
        width: 100px;
        height: 100px;
    }
    
    .provider-card-body {
        padding: 1rem;
    }
}
</style>
