@extends('layouts.provider-dashboard')

@section('content')
<style>
    .bookings-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 16px;
    }
    
    .bookings-title {
        font-size: 28px;
        font-weight: 700;
        color: #09122C;
        margin: 0;
    }
    
    .filter-form {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
    
    .filter-select {
        min-width: 160px;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 14px;
        font-weight: 500;
        color: #374151;
        transition: all 0.3s ease;
        background-color: #fff;
    }
    
    .filter-select:focus {
        outline: none;
        border-color: #872341;
        box-shadow: 0 0 0 3px rgba(135, 35, 65, 0.1);
    }
    
    .filter-btn {
        background: linear-gradient(135deg, #872341, #BE3144);
        border: none;
        border-radius: 10px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .filter-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
    }
    
    .bookings-table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .bookings-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .bookings-table thead th {
        background: #f9fafb;
        padding: 16px 20px;
        font-size: 13px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .bookings-table tbody td {
        padding: 20px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #374151;
    }
    
    .bookings-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .bookings-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .customer-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .customer-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        color: #fff;
        background: linear-gradient(135deg, #872341, #BE3144);
        flex-shrink: 0;
    }
    
    .customer-details {
        display: flex;
        flex-direction: column;
    }
    
    .customer-name {
        font-weight: 600;
        color: #09122C;
        font-size: 15px;
        margin-bottom: 2px;
    }
    
    .customer-email {
        font-size: 13px;
        color: #6b7280;
    }
    
    .date-time-cell {
        display: flex;
        flex-direction: column;
    }
    
    .date-text {
        font-weight: 600;
        color: #09122C;
        font-size: 14px;
        margin-bottom: 2px;
    }
    
    .time-text {
        font-size: 13px;
        color: #6b7280;
    }
    
    .service-info {
        display: flex;
        flex-direction: column;
    }
    
    .service-name {
        font-weight: 600;
        color: #09122C;
        font-size: 14px;
        margin-bottom: 2px;
    }
    
    .service-duration {
        font-size: 13px;
        color: #6b7280;
    }
    
    .amount-text {
        font-size: 16px;
        font-weight: 700;
        color: #872341;
    }
    
    .status-badge {
        display: inline-flex;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .status-pending {
        background: #FEF3C7;
        color: #92400E;
    }
    
    .status-confirmed {
        background: #DBEAFE;
        color: #1E40AF;
    }
    
    .status-completed {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .status-cancelled {
        background: #FEE2E2;
        color: #991B1B;
    }
    
    .action-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .action-btn-confirm {
        background: #10B981;
        color: #fff;
    }
    
    .action-btn-confirm:hover {
        background: #059669;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .action-btn-complete {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: #fff;
    }
    
    .action-btn-complete:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
    }
    
    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }
    
    .empty-icon {
        font-size: 64px;
        color: #d1d5db;
        margin-bottom: 16px;
    }
    
    .empty-text {
        font-size: 16px;
        color: #6b7280;
        margin: 0;
    }
    
    .pagination-wrapper {
        padding: 20px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
    }
    
    @media (max-width: 768px) {
        .bookings-header {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-form {
            width: 100%;
        }
        
        .filter-select {
            flex: 1;
        }
    }
</style>

<div class="bookings-header">
    <h1 class="bookings-title">
        <i class="bi bi-calendar-check me-2"></i>My Bookings
    </h1>
    <form method="GET" class="filter-form">
        <select name="status" class="filter-select">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <button type="submit" class="filter-btn">
            <i class="bi bi-funnel me-1"></i>Filter
        </button>
    </form>
</div>

<div class="bookings-table-card">
    <div style="overflow-x: auto;">
        <table class="bookings-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Customer</th>
                    <th>Service</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($appointments as $appointment)
                <tr>
                    <td>
                        <div class="date-time-cell">
                            <span class="date-text">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</span>
                            <span class="time-text">
                                <i class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}
                            </span>
                        </div>
                    </td>
                    <td>
                        <div class="customer-info">
                            <div class="customer-avatar">
                                {{ strtoupper(substr($appointment->user->name, 0, 2)) }}
                            </div>
                            <div class="customer-details">
                                <div class="customer-name">{{ $appointment->user->name }}</div>
                                <div class="customer-email">{{ $appointment->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="service-info">
                            @if($appointment->services && $appointment->services->count() > 0)
                                <span class="service-name">
                                    {{ $appointment->services->first()->name }}
                                    @if($appointment->services->count() > 1)
                                        <span style="color: #6b7280; font-size: 12px;"> +{{ $appointment->services->count() - 1 }} more</span>
                                    @endif
                                </span>
                                <span class="service-duration">
                                    <i class="bi bi-stopwatch me-1"></i>{{ $appointment->duration }} mins
                                </span>
                            @else
                                <span class="service-name">No service</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <span class="amount-text">{{ Settings::formatPrice($appointment->total_amount) }}</span>
                    </td>
                    <td>
                        @if($appointment->status === 'pending')
                            <span class="status-badge status-pending">
                                <i class="bi bi-clock-history me-1"></i>Pending
                            </span>
                        @elseif($appointment->status === 'confirmed')
                            <span class="status-badge status-confirmed">
                                <i class="bi bi-check-circle me-1"></i>Confirmed
                            </span>
                        @elseif($appointment->status === 'completed')
                            <span class="status-badge status-completed">
                                <i class="bi bi-check-circle-fill me-1"></i>Completed
                            </span>
                        @else
                            <span class="status-badge status-cancelled">
                                <i class="bi bi-x-circle me-1"></i>Cancelled
                            </span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('provider.booking.details', $appointment) }}" class="action-btn" style="background: linear-gradient(135deg, #6366f1, #4f46e5); text-decoration: none;color:#fff;">
                                <i class="bi bi-eye me-1"></i>View
                            </a>
                            @if($appointment->status === 'pending')
                                <form method="POST" action="{{ route('provider.bookings.update-status', $appointment) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="action-btn action-btn-confirm">
                                        <i class="bi bi-check-lg me-1"></i>Confirm
                                    </button>
                                </form>
                            @elseif($appointment->status === 'confirmed')
                                <form method="POST" action="{{ route('provider.bookings.update-status', $appointment) }}" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="action-btn action-btn-complete">
                                        <i class="bi bi-check-circle me-1"></i>Complete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-calendar-x"></i>
                            </div>
                            <p class="empty-text">No bookings found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($appointments->hasPages())
    <div class="pagination-wrapper">
        {{ $appointments->links() }}
    </div>
    @endif
</div>
@endsection
