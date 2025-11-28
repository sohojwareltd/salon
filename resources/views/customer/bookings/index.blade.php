<x-customer-dashboard title="My Bookings">
<style>
    .page-header {
        background: white;
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
    }

    .filter-tabs {
        background: white;
        border-radius: 20px;
        padding: 8px;
        margin-bottom: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        display: flex;
        gap: 8px;
    }

    .filter-tab {
        flex: 1;
        padding: 12px 20px;
        border-radius: 14px;
        border: none;
        background: transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
    }

    .filter-tab:hover {
        background: rgba(135, 35, 65, 0.1);
        color: #872341;
    }

    .filter-tab.active {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
    }

    .booking-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        margin-bottom: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.3s;
        border: 2px solid transparent;
    }

    .booking-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
        border-color: #872341;
    }

    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        border: none;
        transition: all 0.3s;
        cursor: pointer;
    }

    .btn-pay {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(59, 130, 246, 0.3);
    }

    .btn-review {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-review:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
    }
</style>

<!-- Page Header -->
<div class="page-header">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">
                <i class="bi bi-calendar-check-fill me-2" style="color: #872341;"></i>My Bookings
            </h2>
            <p style="color: #64748b; margin: 0; font-size: 14px;">
                View and manage all your appointments
            </p>
        </div>
        <a href="{{ route('providers.index') }}" style="padding: 12px 28px; background: linear-gradient(135deg, #872341, #BE3144); color: white; border-radius: 12px; font-weight: 600; text-decoration: none; transition: all 0.3s;">
            <i class="bi bi-plus-circle me-2"></i>New Booking
        </a>
    </div>
</div>

<!-- Filter Tabs -->
<div class="filter-tabs">
    <button class="filter-tab active" onclick="filterBookings('all')">
        <i class="bi bi-list-ul me-2"></i>All Bookings
    </button>
    <button class="filter-tab" onclick="filterBookings('upcoming')">
        <i class="bi bi-clock me-2"></i>Upcoming
    </button>
    <button class="filter-tab" onclick="filterBookings('completed')">
        <i class="bi bi-check-circle me-2"></i>Completed
    </button>
    <button class="filter-tab" onclick="filterBookings('cancelled')">
        <i class="bi bi-x-circle me-2"></i>Cancelled
    </button>
</div>

<!-- Appointments List -->
<div id="bookingsContainer">
    @forelse($appointments as $appointment)
        <div class="booking-card" data-status="{{ $appointment->status }}">
            <div class="row g-4">
                <!-- Left Section: Date & Time -->
                <div class="col-lg-2">
                    <div style="text-align: center; padding: 16px; background: linear-gradient(135deg, rgba(135, 35, 65, 0.1), rgba(190, 49, 68, 0.1)); border-radius: 16px;">
                        <div style="font-size: 32px; font-weight: 800; color: #872341; line-height: 1;">
                            {{ $appointment->appointment_date->format('d') }}
                        </div>
                        <div style="font-size: 14px; font-weight: 700; color: #BE3144; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $appointment->appointment_date->format('M') }}
                        </div>
                        <div style="font-size: 12px; color: #64748b; margin-top: 8px;">
                            {{ $appointment->appointment_date->format('Y') }}
                        </div>
                        <div style="margin-top: 12px; padding-top: 12px; border-top: 2px solid rgba(135, 35, 65, 0.2);">
                            <i class="bi bi-clock-fill" style="color: #872341; font-size: 14px;"></i>
                            <div style="font-size: 16px; font-weight: 700; color: #1e293b; margin-top: 4px;">
                                {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Middle Section: Details -->
                <div class="col-lg-7">
                    <div style="height: 100%; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <h5 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 12px;">
                                <i class="bi bi-shop" style="color: #872341; font-size: 18px;"></i>
                                {{ $appointment->provider->name }}
                            </h5>
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px;">
                                <div>
                                    <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Service</div>
                                    <div style="font-size: 15px; font-weight: 600; color: #1e293b;">
                                        <i class="bi bi-scissors" style="color: #872341;"></i>
                                        {{ $appointment->service->name }}
                                    </div>
                                </div>
                                <div>
                                    <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Provider</div>
                                    <div style="font-size: 15px; font-weight: 600; color: #1e293b;">
                                        <i class="bi bi-person-fill" style="color: #872341;"></i>
                                        {{ $appointment->provider->user->name }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 12px; margin-top: 16px;">
                            <span class="status-badge" style="
                                @if($appointment->status === 'completed') background: linear-gradient(135deg, #10b981, #059669); color: white;
                                @elseif($appointment->status === 'confirmed') background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;
                                @elseif($appointment->status === 'pending') background: linear-gradient(135deg, #f59e0b, #f97316); color: white;
                                @else background: linear-gradient(135deg, #ef4444, #dc2626); color: white;
                                @endif">
                                <i class="bi 
                                    @if($appointment->status === 'completed') bi-check-circle-fill
                                    @elseif($appointment->status === 'confirmed') bi-calendar-check-fill
                                    @elseif($appointment->status === 'pending') bi-clock-fill
                                    @else bi-x-circle-fill
                                    @endif me-1"></i>
                                {{ ucfirst($appointment->status) }}
                            </span>
                            <span class="status-badge" style="{{ $appointment->payment_status === 'paid' ? 'background: linear-gradient(135deg, #10b981, #059669);' : 'background: linear-gradient(135deg, #f59e0b, #f97316);' }} color: white;">
                                <i class="bi {{ $appointment->payment_status === 'paid' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }} me-1"></i>
                                {{ $appointment->payment_status === 'paid' ? 'Paid' : 'Payment Pending' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Price & Actions -->
                <div class="col-lg-3">
                    <div style="height: 100%; display: flex; flex-direction: column; justify-content: space-between; align-items: flex-end; text-align: right;">
                        <div>
                            <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">Total Amount</div>
                            <div style="font-size: 32px; font-weight: 800; color: #872341; line-height: 1;">
                                ${{ number_format($appointment->total_amount, 2) }}
                            </div>
                        </div>
                        
                        <div style="display: flex; flex-direction: column; gap: 8px; width: 100%;">
                            <a href="{{ route('customer.booking.details', $appointment) }}" class="btn-action" style="background: linear-gradient(135deg, #6366f1, #4f46e5); text-align: center; text-decoration: none;color:#fff">
                                <i class="bi bi-eye me-2"></i>View Details
                            </a>
                            @if($appointment->status === 'confirmed' && $appointment->payment_status !== 'paid')
                                <a href="{{ route('customer.payment.show', $appointment) }}" class="btn-action btn-pay" style="text-align: center; text-decoration: none;">
                                    <i class="bi bi-credit-card me-2"></i>Pay Now
                                </a>
                            @endif
                            @if($appointment->status === 'completed' && $appointment->payment_status === 'paid')
                                <a href="{{ route('customer.review', $appointment) }}" class="btn-action btn-review" style="text-align: center; text-decoration: none;">
                                    <i class="bi bi-star-fill me-2"></i>Write Review
                                </a>
                            @endif
                            @if($appointment->payment_status === 'paid' && $appointment->status === 'confirmed')
                                <button class="btn-action" style="background: #10b981; color: white; cursor: default;">
                                    <i class="bi bi-check-circle-fill me-2"></i>Payment Complete
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 80px 20px; background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);">
            <div style="width: 120px; height: 120px; margin: 0 auto 24px; background: linear-gradient(135deg, rgba(135, 35, 65, 0.1), rgba(190, 49, 68, 0.1)); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-calendar-x" style="font-size: 60px; color: #872341; opacity: 0.6;"></i>
            </div>
            <h4 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 12px;">No bookings found</h4>
            <p style="color: #64748b; margin-bottom: 24px;">Start booking your favorite salon services today!</p>
            <a href="{{ route('providers.index') }}" style="padding: 14px 32px; background: linear-gradient(135deg, #872341, #BE3144); color: white; border-radius: 12px; font-weight: 600; text-decoration: none; display: inline-block;">
                <i class="bi bi-search me-2"></i>Browse Salons
            </a>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($appointments->hasPages())
<div style="margin-top: 24px;">
    <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);">
        {{ $appointments->links() }}
    </div>
</div>
@endif

<script>
    // Filter functionality
    function filterBookings(status) {
        // Update active tab
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.target.closest('.filter-tab').classList.add('active');

        // Filter cards
        const cards = document.querySelectorAll('.booking-card');
        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            
            if (status === 'all') {
                card.style.display = 'block';
            } else if (status === 'upcoming') {
                // Show pending and confirmed
                if (cardStatus === 'pending' || cardStatus === 'confirmed') {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            } else {
                if (cardStatus === status) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            }
        });

        // Add animation
        cards.forEach((card, index) => {
            if (card.style.display !== 'none') {
                card.style.animation = 'fadeInUp 0.5s ease forwards';
                card.style.animationDelay = `${index * 0.1}s`;
            }
        });
    }

    // Add animation styles
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    `;
    document.head.appendChild(style);
</script>
</x-customer-dashboard>
