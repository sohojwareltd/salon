@extends('layouts.provider-dashboard')

@section('content')
<style>
    .reviews-header {
        margin-bottom: 28px;
    }
    
    .reviews-title {
        font-size: 28px;
        font-weight: 700;
        color: #09122C;
        margin: 0;
    }
    
    .rating-summary-card {
        background: linear-gradient(135deg, #872341, #BE3144);
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 16px rgba(135, 35, 65, 0.2);
        margin-bottom: 28px;
        color: #fff;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 32px;
    }
    
    .summary-item {
        text-align: center;
        position: relative;
    }
    
    .summary-item:not(:last-child)::after {
        content: '';
        position: absolute;
        right: -16px;
        top: 50%;
        transform: translateY(-50%);
        width: 1px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
    }
    
    .summary-label {
        font-size: 13px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
        margin-bottom: 12px;
    }
    
    .summary-value {
        font-size: 48px;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 12px;
    }
    
    .rating-stars {
        display: flex;
        justify-content: center;
        gap: 4px;
        font-size: 20px;
    }
    
    .rating-stars i {
        color: #FCD34D;
    }
    
    .rating-stars i.empty {
        opacity: 0.3;
    }
    
    .summary-detail {
        font-size: 13px;
        opacity: 0.8;
        margin-top: 8px;
    }
    
    .review-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }
    
    .review-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }
    
    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .reviewer-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #872341, #BE3144);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }
    
    .reviewer-details {
        display: flex;
        flex-direction: column;
    }
    
    .reviewer-name {
        font-size: 16px;
        font-weight: 600;
        color: #09122C;
        margin-bottom: 2px;
    }
    
    .review-date {
        font-size: 13px;
        color: #9ca3af;
    }
    
    .review-rating {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .review-stars {
        display: flex;
        gap: 2px;
        font-size: 16px;
    }
    
    .review-stars i.filled {
        color: #FCD34D;
    }
    
    .review-stars i.empty {
        color: #d1d5db;
    }
    
    .review-score {
        font-size: 16px;
        font-weight: 700;
        color: #09122C;
    }
    
    .review-comment {
        font-size: 15px;
        line-height: 1.6;
        color: #374151;
        margin-bottom: 16px;
    }
    
    .review-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 16px;
        border-top: 1px solid #f3f4f6;
    }
    
    .review-service {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 14px;
        color: #6b7280;
    }
    
    .review-service i {
        color: #872341;
    }
    
    .review-appointment-date {
        font-size: 14px;
        color: #9ca3af;
    }
    
    .empty-state {
        background: #fff;
        border-radius: 16px;
        padding: 80px 32px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        text-align: center;
    }
    
    .empty-icon {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 20px;
    }
    
    .empty-title {
        font-size: 20px;
        font-weight: 600;
        color: #09122C;
        margin-bottom: 8px;
    }
    
    .empty-text {
        font-size: 15px;
        color: #6b7280;
        margin: 0;
    }
    
    .pagination-wrapper {
        margin-top: 28px;
    }
    
    @media (max-width: 768px) {
        .summary-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }
        
        .summary-item:not(:last-child)::after {
            display: none;
        }
        
        .review-header {
            flex-direction: column;
            gap: 12px;
        }
        
        .review-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
    }
</style>

<div class="reviews-header">
    <h1 class="reviews-title">
        <i class="bi bi-star-fill me-2"></i>Customer Reviews
    </h1>
</div>

<!-- Rating Summary -->
<div class="rating-summary-card">
    <div class="summary-grid">
        <div class="summary-item">
            <div class="summary-label">Average Rating</div>
            <div class="summary-value">{{ number_format($provider->average_rating ?? 0, 1) }}</div>
            <div class="rating-stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= floor($provider->average_rating ?? 0))
                        <i class="bi bi-star-fill"></i>
                    @else
                        <i class="bi bi-star empty"></i>
                    @endif
                @endfor
            </div>
        </div>
        
        <div class="summary-item">
            <div class="summary-label">Total Reviews</div>
            <div class="summary-value">{{ $provider->total_reviews ?? 0 }}</div>
            <div class="summary-detail">From customers</div>
        </div>
        
        <div class="summary-item">
            <div class="summary-label">Completion Rate</div>
            @php
                $totalAppointments = $provider->appointments()->count();
                $completedAppointments = $provider->appointments()->where('status', 'completed')->count();
                $completionRate = $totalAppointments > 0 ? round(($completedAppointments / $totalAppointments) * 100, 1) : 0;
            @endphp
            <div class="summary-value">{{ $completionRate }}%</div>
            <div class="summary-detail">Bookings completed</div>
        </div>
    </div>
</div>

<!-- Reviews List -->
@forelse($reviews as $review)
<div class="review-card">
    <div class="review-header">
        <div class="reviewer-info">
            <div class="reviewer-avatar">
                {{ strtoupper(substr($review->user->name, 0, 2)) }}
            </div>
            <div class="reviewer-details">
                <div class="reviewer-name">{{ $review->user->name }}</div>
                <div class="review-date">
                    <i class="bi bi-clock me-1"></i>{{ $review->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        
        <div class="review-rating">
            <div class="review-stars">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->rating)
                        <i class="bi bi-star-fill filled"></i>
                    @else
                        <i class="bi bi-star empty"></i>
                    @endif
                @endfor
            </div>
            <span class="review-score">{{ $review->rating }}.0</span>
        </div>
    </div>

    @if($review->comment)
    <div class="review-comment">
        "{{ $review->comment }}"
    </div>
    @endif

    <div class="review-footer">
        <div class="review-service">
            <i class="bi bi-scissors"></i>
            <span>{{ $review->appointment->service->name }}</span>
        </div>
        <div class="review-appointment-date">
            {{ \Carbon\Carbon::parse($review->appointment->appointment_date)->format('M d, Y') }}
        </div>
    </div>
</div>
@empty
<div class="empty-state">
    <div class="empty-icon">
        <i class="bi bi-star"></i>
    </div>
    <div class="empty-title">No reviews yet</div>
    <p class="empty-text">Complete bookings to start receiving customer reviews</p>
</div>
@endforelse

@if($reviews->hasPages())
<div class="pagination-wrapper">
    {{ $reviews->links() }}
</div>
@endif
@endsection
