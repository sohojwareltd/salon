@extends('layouts.provider-dashboard')

@section('page-title', 'Dashboard')

@section('content')
<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #872341;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
    }

    .stat-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #872341, #BE3144);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        margin-bottom: 16px;
    }

    .stat-label {
        font-size: 13px;
        color: #666;
        font-weight: 500;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 32px;
        font-weight: 700;
        color: #09122C;
        margin-bottom: 8px;
    }

    .stat-detail {
        font-size: 12px;
        color: #999;
    }

    .monthly-progress-card {
        background: linear-gradient(135deg, #872341, #BE3144);
        border-radius: 16px;
        padding: 32px;
        box-shadow: 0 4px 16px rgba(135, 35, 65, 0.3);
        color: white;
        margin-bottom: 32px;
    }

    .progress-mini-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        padding: 20px;
        backdrop-filter: blur(10px);
    }

    .progress-mini-card h6 {
        font-size: 13px;
        opacity: 0.9;
        margin-bottom: 12px;
        font-weight: 500;
    }

    .progress-mini-card .value {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 12px;
    }

    .progress-bar-custom {
        height: 8px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-bar-fill {
        height: 100%;
        background: white;
        border-radius: 4px;
        transition: width 0.6s ease;
    }

    .appointments-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        height: 100%;
    }

    .appointments-card h5 {
        font-size: 18px;
        font-weight: 600;
        color: #09122C;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .appointment-item {
        display: flex;
        align-items: flex-start;
        padding: 16px;
        background: #f8f9fa;
        border-radius: 12px;
        margin-bottom: 12px;
        border-left: 4px solid #872341;
        transition: all 0.3s ease;
    }

    .appointment-item:hover {
        background: #e9ecef;
        transform: translateX(4px);
    }

    .appointment-item.completed {
        border-left-color: #28A745;
    }

    .appointment-item.confirmed {
        border-left-color: #007BFF;
    }

    .appointment-item.pending {
        border-left-color: #FFC107;
    }

    .appointment-status {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-completed {
        background: #D4EDDA;
        color: #155724;
    }

    .status-confirmed {
        background: #CCE5FF;
        color: #004085;
    }

    .status-pending {
        background: #FFF3CD;
        color: #856404;
    }

    .chart-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .chart-card h5 {
        font-size: 18px;
        font-weight: 600;
        color: #09122C;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 48px;
        color: #ccc;
        margin-bottom: 12px;
    }

    .empty-state p {
        color: #999;
        margin: 0;
    }
</style>

<!-- Statistics Cards -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-label">Today's Appointments</div>
            <div class="stat-value">{{ $stats['today_appointments'] ?? 0 }}</div>
            <div class="stat-detail">{{ $stats['completed_today'] ?? 0 }} completed</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card" style="border-left-color: #FFC107;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #FFC107, #FF9800);">
                <i class="bi bi-clock-history"></i>
            </div>
            <div class="stat-label">Pending Bookings</div>
            <div class="stat-value">{{ $stats['pending_appointments'] ?? 0 }}</div>
            <div class="stat-detail">{{ $stats['confirmed_appointments'] ?? 0 }} confirmed</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card" style="border-left-color: #28A745;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #28A745, #20C997);">
                <i class="bi bi-wallet2"></i>
            </div>
            <div class="stat-label">Wallet Balance</div>
            <div class="stat-value">{{ Settings::formatPrice($stats['wallet_balance'] ?? 0, false) }}</div>
            <div class="stat-detail">Available to withdraw</div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-lg-3">
        <div class="stat-card" style="border-left-color: #007BFF;">
            <div class="stat-icon" style="background: linear-gradient(135deg, #007BFF, #0056B3);">
                <i class="bi bi-star-fill"></i>
            </div>
            <div class="stat-label">Rating & Reviews</div>
            <div class="stat-value">{{ number_format($stats['average_rating'] ?? 0, 1) }} ⭐</div>
            <div class="stat-detail">{{ $stats['total_reviews'] ?? 0 }} reviews</div>
        </div>
    </div>
</div>

<!-- Monthly Performance -->
<div class="monthly-progress-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1" style="font-size: 22px; font-weight: 700;">Monthly Performance</h4>
            <p class="mb-0" style="font-size: 14px; opacity: 0.9;">{{ now()->format('F Y') }}</p>
        </div>
        <div class="text-end">
            <h2 class="mb-0" style="font-size: 36px; font-weight: 700;">{{ $stats['month_completed'] ?? 0 }}</h2>
            <p class="mb-0" style="font-size: 13px; opacity: 0.9;">Completed Bookings</p>
        </div>
    </div>
    
    <div class="row g-3">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="progress-mini-card">
                <h6>Earnings Target</h6>
                <div class="value">{{ Settings::formatPrice($stats['earnings_target'] ?? 50000, false) }}</div>
                <div class="progress-bar-custom">
                    <div class="progress-bar-fill" style="width: {{ min(100, (($stats['current_month_earnings'] ?? 0) / ($stats['earnings_target'] ?? 1)) * 100) }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="progress-mini-card">
                <h6>Booking Target</h6>
                <div class="value">{{ $stats['month_completed'] ?? 0 }}/{{ $stats['booking_target'] ?? 100 }}</div>
                <div class="progress-bar-custom">
                    <div class="progress-bar-fill" style="width: {{ min(100, (($stats['month_completed'] ?? 0) / ($stats['booking_target'] ?? 1)) * 100) }}%"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="progress-mini-card">
                <h6>Customer Satisfaction</h6>
                <div class="value">{{ number_format($stats['average_rating'] ?? 0, 1) }} ⭐</div>
                <p class="mb-0" style="font-size: 12px; opacity: 0.8;">Based on {{ $stats['total_reviews'] ?? 0 }} reviews</p>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="progress-mini-card">
                <h6>Growth Rate</h6>
                <div class="value">{{ ($stats['growth_rate'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($stats['growth_rate'] ?? 0, 1) }}%</div>
                <p class="mb-0" style="font-size: 12px; opacity: 0.8;">vs last month</p>
            </div>
        </div>
    </div>
</div>

<!-- Today's & Upcoming Appointments -->
<div class="row g-4 mb-4">
    <!-- Today's Appointments -->
    <div class="col-lg-6">
        <div class="appointments-card">
            <h5><i class="bi bi-calendar-day"></i> Today's Appointments</h5>
            <div style="max-height: 400px; overflow-y: auto;">
                @forelse($todayAppointments ?? [] as $appointment)
                    <div class="appointment-item {{ $appointment->status }}">
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 15px; font-weight: 600; color: #09122C;">{{ $appointment->user->name }}</h6>
                            <p class="mb-1" style="font-size: 13px; color: #666;">{{ $appointment->service->name }}</p>
                            <small style="font-size: 12px; color: #999;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}</small>
                        </div>
                        <span class="appointment-status status-{{ $appointment->status }}">
                            {{ ucfirst($appointment->status) }}
                        </span>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-calendar-x"></i>
                        <p>No appointments today</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Upcoming Appointments -->
    <div class="col-lg-6">
        <div class="appointments-card">
            <h5><i class="bi bi-calendar3"></i> Upcoming Appointments</h5>
            <div style="max-height: 400px; overflow-y: auto;">
                @forelse($upcomingAppointments ?? [] as $appointment)
                    <div class="appointment-item">
                        <div class="flex-grow-1">
                            <h6 class="mb-1" style="font-size: 15px; font-weight: 600; color: #09122C;">{{ $appointment->user->name }}</h6>
                            <p class="mb-1" style="font-size: 13px; color: #666;">{{ $appointment->service->name }}</p>
                            <small style="font-size: 12px; color: #999;">
                                {{ $appointment->appointment_date->format('M d, Y') }} at {{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                            </small>
                        </div>
                        <span style="font-weight: 700; color: #872341; font-size: 15px;">{{ Settings::formatPrice($appointment->total_price ?? 0, false) }}</span>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="bi bi-calendar-check"></i>
                        <p>No upcoming appointments</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Weekly Earnings Chart -->
<div class="chart-card">
    <h5><i class="bi bi-graph-up"></i> Weekly Earnings</h5>
    <div style="height: 300px;">
        <canvas id="weeklyEarningsChart"></canvas>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('weeklyEarningsChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($weeklyEarnings['labels'] ?? []),
            datasets: [{
                label: 'Earnings ({{ Settings::currency() }})',
                data: @json($weeklyEarnings['data'] ?? []),
                borderColor: '#872341',
                backgroundColor: 'rgba(135, 35, 65, 0.1)',
                pointBackgroundColor: '#872341',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#09122C',
                    padding: 12,
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    callbacks: {
                        label: function(context) {
                            return 'Earnings: {{ Settings::currency() }}' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '{{ Settings::currency() }}' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
