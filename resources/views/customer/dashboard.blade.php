<x-customer-dashboard title="My Dashboard">
<style>
    .stat-card {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.12);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        opacity: 0.1;
        border-radius: 50%;
    }

    .stat-card-1::before { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .stat-card-2::before { background: linear-gradient(135deg, #f59e0b, #f97316); }
    .stat-card-3::before { background: linear-gradient(135deg, #10b981, #059669); }
    .stat-card-4::before { background: linear-gradient(135deg, #ef4444, #dc2626); }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
        margin-bottom: 16px;
    }

    .stat-label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 36px;
        font-weight: 800;
        color: #1e293b;
        line-height: 1;
        margin-bottom: 12px;
    }

    .stat-badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }
</style>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <!-- Total Appointments -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-card-1">
            <div class="stat-icon" style="background: linear-gradient(135deg, #6366f1, #8b5cf6);">
                <i class="bi bi-clipboard-check"></i>
            </div>
            <div class="stat-label">Total Appointments</div>
            <div class="stat-value">{{ $stats['total_appointments'] }}</div>
            <div class="stat-badge" style="background: #ede9fe; color: #6b21a8;">
                <i class="bi bi-graph-up me-1"></i>All Time
            </div>
        </div>
    </div>

    <!-- Upcoming -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-card-2">
            <div class="stat-icon" style="background: linear-gradient(135deg, #f59e0b, #f97316);">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="stat-label">Upcoming Bookings</div>
            <div class="stat-value">{{ $stats['upcoming_appointments'] }}</div>
            <div class="stat-badge" style="background: #fed7aa; color: #c2410c;">
                <i class="bi bi-clock me-1"></i>Scheduled
            </div>
        </div>
    </div>

    <!-- Completed -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-card-3">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="bi bi-check-circle"></i>
            </div>
            <div class="stat-label">Completed Services</div>
            <div class="stat-value">{{ $stats['completed_appointments'] }}</div>
            <div class="stat-badge" style="background: #d1fae5; color: #065f46;">
                <i class="bi bi-currency-dollar me-1"></i>{{ Settings::formatPrice($stats['total_spent'], false) }}
            </div>
        </div>
    </div>

    <!-- Pending Payments -->
    <div class="col-lg-3 col-md-6">
        <div class="stat-card stat-card-4">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ef4444, #dc2626);">
                <i class="bi bi-credit-card"></i>
            </div>
            <div class="stat-label">Pending Payments</div>
            <div class="stat-value">{{ $stats['pending_payments'] }}</div>
            <div class="stat-badge" style="background: #fee2e2; color: #991b1b;">
                <i class="bi bi-exclamation-triangle me-1"></i>{{ Settings::formatPrice($stats['pending_payments_amount'], false) }}
            </div>
        </div>
    </div>
</div>

<style>
    .payment-alert {
        background: white;
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(239, 68, 68, 0.12);
        border-left: 6px solid #ef4444;
        margin-bottom: 24px;
    }

    .payment-item {
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 12px;
        border: 2px solid #fecaca;
    }

    .btn-pay {
        background: linear-gradient(135deg, #f59e0b, #f97316);
        color: white;
        border: none;
        padding: 10px 24px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(245, 158, 11, 0.3);
        color: white;
    }
</style>

<!-- Pending Payments Alert -->
@if($stats['pending_payments'] > 0)
<div class="payment-alert">
    <div class="d-flex align-items-start gap-3">
        <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #ef4444, #dc2626); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <i class="bi bi-exclamation-triangle-fill" style="color: white; font-size: 24px;"></i>
        </div>
        <div style="flex: 1;">
            <h5 style="font-size: 20px; font-weight: 700; color: #991b1b; margin-bottom: 8px;">
                You have {{ $stats['pending_payments'] }} pending payment(s)
            </h5>
            <p style="color: #b91c1c; margin-bottom: 20px;">
                Total amount due: <strong>{{ Settings::formatPrice($stats['pending_payments_amount']) }}</strong>. Please complete your payments to avoid service interruption.
            </p>
            @if($needsPayment->count() > 0)
            <div>
                @foreach($needsPayment as $appointment)
                <div class="payment-item">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="flex: 1;">
                            <h6 style="font-size: 16px; font-weight: 700; color: #7c2d12; margin-bottom: 6px;">
                                {{ $appointment->service->name }}
                            </h6>
                            <p style="font-size: 14px; color: #9a3412; margin-bottom: 6px;">
                                <i class="bi bi-person me-1"></i>with {{ $appointment->provider->name }}
                            </p>
                            <small style="color: #9a3412;">
                                <i class="bi bi-calendar3 me-1"></i>{{ $appointment->appointment_date->format('M d, Y') }}
                            </small>
                        </div>
                        <div style="text-align: right;">
                            <p style="font-size: 24px; font-weight: 800; color: #991b1b; margin-bottom: 12px;">
                                ৳{{ number_format($appointment->total_price, 0) }}
                            </p>
                            <a href="{{ route('customer.payment', $appointment) }}" class="btn-pay">
                                <i class="bi bi-credit-card me-2"></i>Pay Now
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endif

<style>
    .section-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        overflow: hidden;
    }

    .section-header {
        background: linear-gradient(135deg, #f8fafc, #f1f5f9);
        padding: 20px 24px;
        border-bottom: 2px solid #e2e8f0;
    }

    .section-title {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .section-subtitle {
        font-size: 13px;
        color: #64748b;
        margin: 4px 0 0 0;
    }

    .section-badge {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 700;
    }

    .appointment-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 12px;
        border: 2px solid #e2e8f0;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }

    .appointment-card::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 6px;
        height: 100%;
        background: linear-gradient(135deg, #872341, #BE3144);
    }

    .appointment-card:hover {
        transform: translateX(4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        border-color: #872341;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }

    .btn-view-all {
        color: #872341;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s;
    }

    .btn-view-all:hover {
        color: #BE3144;
    }
</style>

<div class="row g-4 mb-4">
    <!-- Upcoming Appointments -->
    <div class="col-lg-6">
        <div class="section-card">
            <div class="section-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-calendar-event me-2" style="color: #872341;"></i>Upcoming Appointments
                        </h5>
                        <p class="section-subtitle">Your scheduled services</p>
                    </div>
                    <span class="section-badge">
                        {{ $upcomingAppointments->count() }}
                    </span>
                </div>
            </div>
            <div style="padding: 20px; max-height: 500px; overflow-y: auto;">
                @forelse($upcomingAppointments as $appointment)
                    <div class="appointment-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="flex: 1;">
                                <h6 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">
                                    {{ $appointment->service->name }}
                                </h6>
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 12px;">
                                    <i class="bi bi-person-fill me-1" style="color: #872341;"></i>
                                    with <strong>{{ $appointment->provider->name }}</strong>
                                </p>
                                <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                                    <span style="font-size: 13px; color: #64748b;">
                                        <i class="bi bi-calendar3 me-1"></i>{{ $appointment->appointment_date->format('M d, Y') }}
                                    </span>
                                    <span style="font-size: 13px; color: #64748b;">
                                        <i class="bi bi-clock-fill me-1"></i>{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                    </span>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 24px; font-weight: 800; color: #872341; margin-bottom: 12px;">
                                    ৳{{ number_format($appointment->total_price, 0) }}
                                </p>
                                <span style="padding: 6px 16px; border-radius: 20px; font-size: 12px; font-weight: 700; background: {{ $appointment->status === 'confirmed' ? 'linear-gradient(135deg, #10b981, #059669)' : 'linear-gradient(135deg, #f59e0b, #f97316)' }}; color: white; display: inline-block;">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <h6 style="font-weight: 700; margin-bottom: 8px;">No upcoming appointments</h6>
                        <p style="margin: 0;">Book your next service now!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Appointments -->
    <div class="col-lg-6">
        <div class="section-card">
            <div class="section-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="section-title">
                            <i class="bi bi-clock-history me-2" style="color: #872341;"></i>Recent History
                        </h5>
                        <p class="section-subtitle">Your past appointments</p>
                    </div>
                    <a href="{{ route('customer.bookings') }}" class="btn-view-all">
                        View All <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div style="padding: 20px; max-height: 500px; overflow-y: auto;">
                @forelse($recentAppointments as $appointment)
                    <div class="appointment-card">
                        <div class="d-flex justify-content-between align-items-start">
                            <div style="flex: 1;">
                                <h6 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                    {{ $appointment->service->name }}
                                </h6>
                                <p style="font-size: 14px; color: #64748b; margin-bottom: 10px;">
                                    with {{ $appointment->provider->name }}
                                </p>
                                <div style="font-size: 12px; color: #94a3b8;">
                                    <i class="bi bi-calendar3 me-1"></i>{{ $appointment->appointment_date->format('M d, Y') }} • 
                                    <i class="bi bi-clock-fill me-1"></i>{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 10px;">
                                    ৳{{ number_format($appointment->total_price, 0) }}
                                </p>
                                <span style="padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; margin-bottom: 12px; display: inline-block;
                                    @if($appointment->status === 'completed') background: linear-gradient(135deg, #10b981, #059669); color: white;
                                    @elseif($appointment->status === 'confirmed') background: linear-gradient(135deg, #3b82f6, #2563eb); color: white;
                                    @elseif($appointment->status === 'pending') background: linear-gradient(135deg, #f59e0b, #f97316); color: white;
                                    @else background: linear-gradient(135deg, #ef4444, #dc2626); color: white;
                                    @endif">
                                    {{ ucfirst($appointment->status) }}
                                </span>
                                <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 12px;">
                                    @if($appointment->canBePaid())
                                        <a href="{{ route('customer.payment', $appointment) }}" style="padding: 8px 16px; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; text-align: center;">
                                            <i class="bi bi-credit-card me-1"></i>Pay Now
                                        </a>
                                    @endif
                                    @if($appointment->canBeReviewed() && !$appointment->review_submitted)
                                        <a href="{{ route('customer.review', $appointment) }}" style="padding: 8px 16px; background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 8px; font-size: 12px; font-weight: 600; text-decoration: none; text-align: center;">
                                            <i class="bi bi-star-fill me-1"></i>Review
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-clipboard-x"></i>
                        <h6 style="font-weight: 700; margin-bottom: 8px;">No appointment history</h6>
                        <p style="margin: 0;">Start booking to see your history</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<style>
    .cta-card {
        background: linear-gradient(135deg, #872341, #BE3144);
        border-radius: 24px;
        padding: 48px 40px;
        box-shadow: 0 20px 60px rgba(135, 35, 65, 0.3);
        position: relative;
        overflow: hidden;
    }

    .cta-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 400px;
        height: 400px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }

    .cta-card::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: -5%;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }

    .btn-cta {
        background: white;
        color: #872341;
        padding: 16px 40px;
        border-radius: 14px;
        font-size: 16px;
        font-weight: 700;
        border: none;
        transition: all 0.3s;
        position: relative;
        z-index: 1;
    }

    .btn-cta:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.2);
        color: #872341;
    }
</style>

<!-- Call to Action -->
<div class="cta-card" style="margin-top: 24px;">
    <div style="position: relative; z-index: 1; max-width: 600px;">
        <h3 style="font-size: 32px; font-weight: 800; color: white; margin-bottom: 16px;">
            Ready to book your next appointment?
        </h3>
        <p style="font-size: 16px; color: rgba(255, 255, 255, 0.9); margin-bottom: 32px; line-height: 1.6;">
            Browse our expert providers and find the perfect service for you. Quality care is just a click away!
        </p>
        <a href="{{ route('providers.index') }}" class="btn-cta">
            <i class="bi bi-search me-2"></i>Browse Providers & Services
        </a>
    </div>
</div>
</x-customer-dashboard>
