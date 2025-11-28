@extends('layouts.provider-dashboard')

@section('content')
<style>
    .details-header {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(135, 35, 65, 0.3);
    }

    .details-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .details-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .card-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 1rem;
    }

    .status-badge {
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 14px;
        display: inline-block;
    }

    .status-pending {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .status-confirmed {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
    }

    .status-completed {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .status-cancelled {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f3f4f6;
    }

    .info-row:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #6b7280;
        font-weight: 500;
    }

    .info-value {
        color: #111827;
        font-weight: 600;
    }

    .service-item {
        background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .service-name {
        font-weight: 600;
        color: #111827;
    }

    .service-duration {
        color: #6b7280;
        font-size: 14px;
    }

    .service-price {
        font-weight: 700;
        color: #872341;
        font-size: 18px;
    }

    .customer-card {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .customer-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .customer-info h5 {
        color: #1e3a8a;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .customer-info p {
        color: #3730a3;
        margin-bottom: 5px;
    }

    .earnings-card {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .earnings-amount {
        font-size: 32px;
        font-weight: 800;
        color: #065f46;
        margin-bottom: 1rem;
    }

    .earnings-breakdown {
        background: white;
        border-radius: 10px;
        padding: 15px;
    }

    .earnings-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        color: #065f46;
    }

    .action-btn {
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        border: none;
        cursor: pointer;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }

    .btn-confirm {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }

    .btn-complete {
        background: linear-gradient(135deg, #10b981, #059669);
    }

    .btn-back {
        background: linear-gradient(135deg, #6b7280, #4b5563);
    }

    .payment-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }

    .payment-paid {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }

    .payment-pending {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #991b1b;
    }

    .salon-info-card {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .salon-detail-logo {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        object-fit: cover;
        margin-bottom: 10px;
    }
</style>

<div class="container-fluid px-4">
    <!-- Header -->
    <div class="details-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-2">
                    <i class="bi bi-calendar-check me-2"></i>
                    Booking #{{ $appointment->id }}
                </h2>
                <p class="mb-0" style="opacity: 0.9;">Complete booking information and customer details</p>
            </div>
            <div>
                <span class="status-badge status-{{ $appointment->status }}">
                    {{ ucfirst($appointment->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Appointment Information -->
            <div class="details-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #6366f1, #4f46e5);">
                    <i class="bi bi-clock"></i>
                </div>
                <h4 class="mb-3" style="color: #111827; font-weight: 700;">Appointment Information</h4>
                
                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-calendar3 me-2"></i>Date
                    </span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F j, Y') }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-clock me-2"></i>Time
                    </span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('g:i A') }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-hourglass-split me-2"></i>Duration
                    </span>
                    <span class="info-value">{{ $appointment->duration }} minutes</span>
                </div>

                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-credit-card me-2"></i>Payment Status
                    </span>
                    <span>
                        <span class="payment-badge payment-{{ $appointment->payment_status }}">
                            {{ $appointment->payment_status === 'paid' ? 'Paid' : 'Pending' }}
                        </span>
                    </span>
                </div>

                @if($appointment->notes)
                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-chat-left-text me-2"></i>Customer Notes
                    </span>
                    <span class="info-value">{{ $appointment->notes }}</span>
                </div>
                @endif
            </div>

            <!-- Services -->
            <div class="details-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #ec4899, #db2777);">
                    <i class="bi bi-scissors"></i>
                </div>
                <h4 class="mb-3" style="color: #111827; font-weight: 700;">Services</h4>
                
                @foreach($appointment->services as $service)
                <div class="service-item">
                    <div>
                        <div class="service-name">{{ $service->name }}</div>
                        <div class="service-duration">
                            <i class="bi bi-clock me-1"></i>{{ $service->duration }} minutes
                        </div>
                    </div>
                    <div class="service-price">{{ Settings::formatPrice($service->price) }}</div>
                </div>
                @endforeach

                <div class="mt-3 pt-3" style="border-top: 2px solid #f3f4f6;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span style="font-size: 20px; font-weight: 700; color: #111827;">Total Amount</span>
                        <span style="font-size: 24px; font-weight: 800; color: #872341;">
                            {{ Settings::formatPrice($appointment->total_amount) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Information (if paid) -->
            @if($appointment->payment)
            <div class="details-card">
                <div class="card-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <h4 class="mb-3" style="color: #111827; font-weight: 700;">Payment Information</h4>
                
                <div class="info-row">
                    <span class="info-label">Payment ID</span>
                    <span class="info-value">#{{ $appointment->payment->id }}</span>
                </div>

                <div class="info-row">
                    <span class="info-label">Service Amount</span>
                    <span class="info-value">{{ Settings::formatPrice($appointment->payment->service_amount ?? 0) }}</span>
                </div>

                @if($appointment->payment->tip_amount > 0)
                <div class="info-row">
                    <span class="info-label">
                        <i class="bi bi-star-fill me-2" style="color: #f59e0b;"></i>Tip Amount
                    </span>
                    <span class="info-value">{{ Settings::formatPrice($appointment->payment->tip_amount) }}</span>
                </div>
                @endif

                <div class="info-row">
                    <span class="info-label">Total Paid</span>
                    <span class="info-value" style="font-size: 18px; color: #10b981;">
                        {{ Settings::formatPrice($appointment->payment->total_amount ?? $appointment->payment->amount) }}
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Payment Method</span>
                    <span class="info-value">
                        <i class="bi bi-credit-card me-1"></i>Stripe
                    </span>
                </div>

                <div class="info-row">
                    <span class="info-label">Paid At</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($appointment->payment->paid_at)->format('M j, Y g:i A') }}</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="customer-card">
                <div class="text-center mb-3">
                    @if($appointment->customer && $appointment->customer->profile_picture)
                        <img src="{{ asset('storage/' . $appointment->customer->profile_picture) }}" 
                             alt="{{ $appointment->customer->name }}" 
                             class="customer-avatar">
                    @else
                        <div class="customer-avatar" style="background: linear-gradient(135deg, #6366f1, #4f46e5); display: flex; align-items: center; justify-content: center; color: white; font-size: 32px; font-weight: 700;">
                            {{ substr($appointment->customer->name ?? 'C', 0, 1) }}
                        </div>
                    @endif
                </div>
                
                <div class="customer-info text-center">
                    <h5>{{ $appointment->customer->name ?? 'Customer' }}</h5>
                    <p>
                        <i class="bi bi-envelope me-1"></i>
                        {{ $appointment->customer->email ?? 'N/A' }}
                    </p>
                    <p>
                        <i class="bi bi-telephone me-1"></i>
                        {{ $appointment->customer->phone ?? 'N/A' }}
                    </p>
                </div>

                <div class="text-center mt-3">
                    <span style="color: #6b7280; font-size: 13px;">
                        <i class="bi bi-person-badge me-1"></i>
                        Customer ID: #{{ $appointment->customer_id }}
                    </span>
                </div>
            </div>

            <!-- Your Earnings (if completed and paid) -->
            @if($earnings && $appointment->status === 'completed')
            <div class="earnings-card">
                <h5 style="color: #065f46; font-weight: 700; margin-bottom: 10px;">
                    <i class="bi bi-wallet2 me-2"></i>Your Earnings
                </h5>
                
                <div class="earnings-amount">
                    {{ Settings::formatPrice($earnings['total']) }}
                </div>

                <div class="earnings-breakdown">
                    <div class="earnings-row">
                        <span>Service Amount:</span>
                        <span style="font-weight: 600;">{{ Settings::formatPrice($earnings['service_amount']) }}</span>
                    </div>
                    <div class="earnings-row">
                        <span>Your Commission:</span>
                        <span style="font-weight: 600;">${{ number_format($earnings['commission'], 2) }}</span>
                    </div>
                    @if($earnings['tips'] > 0)
                    <div class="earnings-row">
                        <span>
                            <i class="bi bi-star-fill me-1" style="color: #f59e0b;"></i>Tips:
                        </span>
                        <span style="font-weight: 600;">${{ number_format($earnings['tips'], 2) }}</span>
                    </div>
                    @endif
                    <div class="earnings-row" style="border-top: 2px solid #065f46; padding-top: 10px; margin-top: 10px; font-weight: 700; font-size: 16px;">
                        <span>Total Earned:</span>
                        <span>{{ Settings::formatPrice($earnings['total']) }}</span>
                    </div>
                </div>
            </div>
            @endif



            <!-- Quick Actions -->
            <div class="details-card">
                <h5 class="mb-3" style="color: #111827; font-weight: 700;">Quick Actions</h5>
                
                <div class="d-grid gap-2">
                    @if($appointment->status === 'pending')
                    <form action="{{ route('provider.bookings.update-status', $appointment) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="confirmed">
                        <button type="submit" class="action-btn btn-confirm w-100">
                            <i class="bi bi-check-circle"></i>
                            Confirm Appointment
                        </button>
                    </form>
                    @endif

                    @if($appointment->status === 'confirmed')
                    <form action="{{ route('provider.bookings.update-status', $appointment) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="action-btn btn-complete w-100">
                            <i class="bi bi-check-circle-fill"></i>
                            Mark as Completed
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('provider.bookings.index') }}" class="action-btn btn-back w-100">
                        <i class="bi bi-arrow-left"></i>
                        Back to Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
