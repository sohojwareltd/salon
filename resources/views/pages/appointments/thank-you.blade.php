@extends('layouts.app')

@section('content')
<div class="min-vh-100" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); padding: 60px 20px;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <!-- Success Card -->
                <div style="background: white; border-radius: 24px; padding: 48px 32px; text-align: center; box-shadow: 0 20px 60px rgba(0,0,0,0.08);">
                    <!-- Success Icon -->
                    <div style="width: 100px; height: 100px; margin: 0 auto 24px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; animation: scaleIn 0.5s ease;">
                        <i class="bi bi-check-lg" style="font-size: 56px; color: white; font-weight: bold;"></i>
                    </div>

                    <!-- Success Message -->
                    <h1 style="font-size: 32px; font-weight: 800; color: #065f46; margin-bottom: 16px;">
                        Booking Confirmed!
                    </h1>
                    <p style="font-size: 16px; color: #6b7280; line-height: 1.6; margin-bottom: 32px;">
                        Your appointment has been successfully booked. We've sent you a confirmation with all the details.
                    </p>

                    <!-- Appointment Details -->
                    @if(session('appointment'))
                    <div style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-radius: 16px; padding: 24px; margin-bottom: 32px; text-align: left;">
                        <h3 style="font-size: 18px; font-weight: 700; color: #065f46; margin-bottom: 20px; text-align: center;">
                            <i class="bi bi-calendar-check me-2"></i>Appointment Details
                        </h3>

                        <div class="row g-3">
                            <!-- Provider -->
                            <div class="col-12">
                                <div style="display: flex; align-items: center; gap: 12px; padding: 12px; background: white; border-radius: 12px;">
                                    <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #872341, #BE3144); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                        <i class="bi bi-person-fill" style="font-size: 24px; color: white;"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-size: 12px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px;">Provider</div>
                                        <div style="font-size: 16px; font-weight: 600; color: #111827;">{{ session('appointment')->provider->user->name ?? 'Provider' }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Date & Time -->
                            <div class="col-6">
                                <div style="padding: 12px; background: white; border-radius: 12px;">
                                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                        <i class="bi bi-calendar3 me-1"></i>Date
                                    </div>
                                    <div style="font-size: 15px; font-weight: 700; color: #065f46;">
                                        {{ \Carbon\Carbon::parse(session('appointment')->appointment_date)->format('M d, Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div style="padding: 12px; background: white; border-radius: 12px;">
                                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                        <i class="bi bi-clock me-1"></i>Time
                                    </div>
                                    <div style="font-size: 15px; font-weight: 700; color: #065f46;">
                                        {{ \Carbon\Carbon::parse(session('appointment')->start_time, 'UTC')->format('h:i A') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Services -->
                            <div class="col-12">
                                <div style="padding: 12px; background: white; border-radius: 12px;">
                                    <div style="font-size: 11px; color: #6b7280; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">
                                        <i class="bi bi-scissors me-1"></i>Services ({{ session('appointment')->services->count() }})
                                    </div>
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        @foreach(session('appointment')->services as $service)
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-size: 14px; color: #111827; font-weight: 500;">{{ $service->name }}</span>
                                            <span style="font-size: 13px; color: #10b981; font-weight: 600;">{{ Settings::formatPrice($service->price, false) }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="col-12">
                                <div style="padding: 16px; background: linear-gradient(135deg, #065f46, #047857); border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 14px; color: #d1fae5; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600;">Total Amount</span>
                                    <span style="font-size: 24px; color: white; font-weight: 800;">{{ Settings::formatPrice(session('appointment')->total_amount, false) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        <a href="{{ route('customer.bookings') }}" style="flex: 1; padding: 14px 24px; background: linear-gradient(135deg, #872341, #BE3144); color: white; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s;">
                            <i class="bi bi-calendar-check"></i>
                            View My Appointments
                        </a>
                        <a href="{{ route('home') }}" style="flex: 1; padding: 14px 24px; background: #f3f4f6; color: #374151; text-decoration: none; border-radius: 12px; font-weight: 600; display: inline-flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.3s;">
                            <i class="bi bi-house"></i>
                            Back to Home
                        </a>
                    </div>

                    <!-- Additional Info -->
                    <div style="margin-top: 32px; padding-top: 32px; border-top: 1px solid #e5e7eb;">
                        <div style="display: flex; align-items: start; gap: 12px; text-align: left; background: #fef3c7; padding: 16px; border-radius: 12px; border-left: 4px solid #f59e0b;">
                            <i class="bi bi-info-circle-fill" style="font-size: 20px; color: #f59e0b; margin-top: 2px;"></i>
                            <div style="flex: 1;">
                                <p style="font-size: 14px; color: #92400e; margin: 0; line-height: 1.5;">
                                    <strong>Please Note:</strong> Your appointment is currently <strong>pending</strong>. The provider will confirm it shortly. You'll receive a notification once confirmed.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scaleIn {
    0% {
        transform: scale(0);
        opacity: 0;
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
        opacity: 1;
    }
}

a:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.12);
}
</style>
@endsection
