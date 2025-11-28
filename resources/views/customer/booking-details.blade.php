<x-customer-dashboard title="Booking Details">
<style>
    .details-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    .details-header {
        background: linear-gradient(135deg, #872341, #BE3144);
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    .details-header::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }
    .details-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }
    .section-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }
    .section-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #872341, #BE3144);
        border-radius: 12px;
        display: flex;
        align-items: center; justify-content: center;
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .service-card {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .status-badge-large {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 16px;
    }
    .timeline-item {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        position: relative;
    }
    .timeline-item:last-child::after {
        display: none;
    }
    .timeline-item::after {
        content: '';
        position: absolute;
        left: 19px;
        top: 48px;
        width: 2px;
        height: calc(100% - 24px);
        background: #e2e8f0;
    }
    .timeline-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        z-index: 1;
    }
</style>

<div class="details-container">
    <!-- Page Header -->
    <div class="details-header">
        <div style="position: relative; z-index: 1;">
            <div style="display: flex; justify-content: space-between; align-items: start;">
                <div>
                    <div style="display: inline-block; background: rgba(255,255,255,0.2); padding: 8px 16px; border-radius: 8px; margin-bottom: 12px;">
                        <span style="font-size: 13px; color: rgba(255,255,255,0.9); font-weight: 600;">
                            Booking ID: #{{ $appointment->id }}
                        </span>
                    </div>
                    <h2 style="font-size: 32px; font-weight: 800; color: white; margin-bottom: 8px;">
                        Booking Details
                    </h2>
                    <p style="font-size: 15px; color: rgba(255,255,255,0.9); margin: 0;">
                        Complete information about your appointment
                    </p>
                </div>
                <div style="text-align: right;">
                    <div class="status-badge-large" style="
                        @if($appointment->status === 'completed') background: linear-gradient(135deg, #10b981, #059669);
                        @elseif($appointment->status === 'confirmed') background: linear-gradient(135deg, #3b82f6, #2563eb);
                        @elseif($appointment->status === 'pending') background: linear-gradient(135deg, #f59e0b, #f97316);
                        @else background: linear-gradient(135deg, #ef4444, #dc2626);
                        @endif
                        color: white;">
                        <i class="bi 
                            @if($appointment->status === 'completed') bi-check-circle-fill
                            @elseif($appointment->status === 'confirmed') bi-clock-fill
                            @elseif($appointment->status === 'pending') bi-hourglass-split
                            @else bi-x-circle-fill
                            @endif"></i>
                        {{ ucfirst($appointment->status) }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Appointment Information -->
            <div class="details-card">
                <div class="section-title">
                    <div class="section-icon">
                        <i class="bi bi-calendar-check-fill" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">
                        Appointment Information
                    </h5>
                </div>

                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">
                        <i class="bi bi-calendar-event me-2" style="color: #872341;"></i>Date
                    </span>
                    <span style="color: #1e293b; font-weight: 600;">
                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('l, F d, Y') }}
                    </span>
                </div>

                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">
                        <i class="bi bi-clock-fill me-2" style="color: #872341;"></i>Time
                    </span>
                    <span style="color: #1e293b; font-weight: 600;">
                        {{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($appointment->end_time)->format('h:i A') }}
                    </span>
                </div>

                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">
                        <i class="bi bi-hourglass me-2" style="color: #872341;"></i>Duration
                    </span>
                    <span style="color: #1e293b; font-weight: 600;">
                        {{ $appointment->services->sum('duration') }} minutes
                    </span>
                </div>

                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">
                        <i class="bi bi-cash-stack me-2" style="color: #872341;"></i>Payment Status
                    </span>
                    <span style="
                        {{ $appointment->payment_status === 'paid' ? 'background: #dcfce7; color: #15803d;' : 'background: #fef3c7; color: #78350f;' }}
                        padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 13px;">
                        <i class="bi {{ $appointment->payment_status === 'paid' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill' }} me-1"></i>
                        {{ $appointment->payment_status === 'paid' ? 'Paid' : 'Pending' }}
                    </span>
                </div>
            </div>

            <!-- Services -->
            <div class="details-card">
                <div class="section-title">
                    <div class="section-icon" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                        <i class="bi bi-scissors" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">
                        Services
                    </h5>
                </div>

                @foreach($appointment->services as $service)
                <div class="service-card">
                    <div>
                        <div style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                            {{ $service->service_name }}
                        </div>
                        <div style="font-size: 14px; color: #64748b;">
                            <i class="bi bi-clock me-1"></i>{{ $service->duration }} minutes
                        </div>
                    </div>
                    <div style="font-size: 24px; font-weight: 800; color: #10b981;">
                        {{ Settings::formatPrice($service->price) }}
                    </div>
                </div>
                @endforeach

                <div style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-radius: 12px; padding: 20px; margin-top: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 16px; color: #166534; font-weight: 600;">Total Amount</span>
                        <span style="font-size: 32px; font-weight: 800; color: #15803d;">
                            {{ Settings::formatPrice($appointment->total_amount) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($appointment->payment)
            <div class="details-card">
                <div class="section-title">
                    <div class="section-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                        <i class="bi bi-credit-card-fill" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h5 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">
                        Payment Information
                    </h5>
                </div>

                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">Payment ID</span>
                    <span style="color: #1e293b; font-weight: 600;">#{{ $appointment->payment->id }}</span>
                </div>

                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">Service Amount</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ Settings::formatPrice($appointment->payment->service_amount) }}</span>
                </div>

                @if($appointment->payment->tip_amount > 0)
                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">Tip</span>
                    <span style="color: #f59e0b; font-weight: 600;">{{ Settings::formatPrice($appointment->payment->tip_amount) }}</span>
                </div>
                @endif

                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">Total Paid</span>
                    <span style="color: #10b981; font-weight: 700; font-size: 18px;">{{ Settings::formatPrice($appointment->payment->total_amount) }}</span>
                </div>

                @if($appointment->payment->paid_at)
                <div class="info-row">
                    <span style="color: #64748b; font-weight: 500;">Paid At</span>
                    <span style="color: #1e293b; font-weight: 600;">{{ $appointment->payment->paid_at->format('M d, Y h:i A') }}</span>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Salon Information -->
            <div class="details-card">
                <div class="section-title">
                    <div class="section-icon" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                        <i class="bi bi-shop" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h5 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                        Salon
                    </h5>
                </div>

                <div style="text-align: center; margin-bottom: 20px;">
                    @if($appointment->salon->logo)
                        <img src="{{ asset('storage/' . $appointment->salon->logo) }}" alt="{{ $appointment->salon->salon_name }}" 
                             style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid #f1f5f9;">
                    @else
                        <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, #872341, #BE3144); margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-shop" style="font-size: 48px; color: white;"></i>
                        </div>
                    @endif
                </div>

                <h6 style="font-size: 20px; font-weight: 700; color: #1e293b; text-align: center; margin-bottom: 16px;">
                    {{ $appointment->salon->salon_name }}
                </h6>

                <div style="font-size: 14px; color: #64748b; text-align: center; margin-bottom: 8px;">
                    <i class="bi bi-geo-alt-fill me-1" style="color: #872341;"></i>
                    {{ $appointment->salon->address }}
                </div>

                <div style="font-size: 14px; color: #64748b; text-align: center; margin-bottom: 16px;">
                    <i class="bi bi-telephone-fill me-1" style="color: #872341;"></i>
                    {{ $appointment->salon->phone }}
                </div>

                @if($appointment->salon->hasSubdomain())
                    <a href="{{ $appointment->salon->subdomain_url }}" target="_blank"
                       style="display: block; text-align: center; padding: 12px; background: linear-gradient(135deg, #872341, #BE3144); color: white; border-radius: 12px; font-weight: 600; text-decoration: none;">
                        <i class="bi bi-eye me-2"></i>View Salon Profile
                    </a>
                @endif
            </div>

            <!-- Provider Information -->
            <div class="details-card">
                <div class="section-title">
                    <div class="section-icon" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                        <i class="bi bi-person-fill" style="font-size: 24px; color: white;"></i>
                    </div>
                    <h5 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                        Provider
                    </h5>
                </div>

                <div style="text-align: center; margin-bottom: 20px;">
                    @if($appointment->provider->profile_picture)
                        <img src="{{ asset('storage/' . $appointment->provider->profile_picture) }}" alt="{{ $appointment->provider->name }}" 
                             style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 4px solid #f1f5f9;">
                    @else
                        <div style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6, #7c3aed); margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                            <span style="font-size: 32px; font-weight: 800; color: white;">
                                {{ substr($appointment->provider->name, 0, 1) }}
                            </span>
                        </div>
                    @endif
                </div>

                <h6 style="font-size: 18px; font-weight: 700; color: #1e293b; text-align: center; margin-bottom: 8px;">
                    {{ $appointment->provider->name }}
                </h6>

                <div style="font-size: 13px; color: #64748b; text-align: center; margin-bottom: 16px;">
                    {{ $appointment->provider->specialization }}
                </div>

                <a href="{{ route('providers.show', $appointment->provider) }}" 
                   style="display: block; text-align: center; padding: 12px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: white; border-radius: 12px; font-weight: 600; text-decoration: none;">
                    <i class="bi bi-eye me-2"></i>View Provider Profile
                </a>
            </div>

            <!-- Actions -->
            <div class="details-card">
                <h6 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 16px;">
                    Quick Actions
                </h6>

                @if($appointment->status === 'confirmed' && $appointment->payment_status !== 'paid')
                    <a href="{{ route('customer.payment.show', $appointment) }}" 
                       style="display: block; text-align: center; padding: 14px; background: linear-gradient(135deg, #10b981, #059669); color: white; border-radius: 12px; font-weight: 600; text-decoration: none; margin-bottom: 12px;">
                        <i class="bi bi-credit-card me-2"></i>Pay Now
                    </a>
                @endif

                <a href="{{ route('customer.bookings') }}" 
                   style="display: block; text-align: center; padding: 14px; background: #f1f5f9; color: #64748b; border-radius: 12px; font-weight: 600; text-decoration: none;">
                    <i class="bi bi-arrow-left me-2"></i>Back to Bookings
                </a>
            </div>
        </div>
    </div>
</div>
</x-customer-dashboard>
