<x-customer-dashboard title="Payment Successful">
<style>
    .success-container {
        max-width: 700px;
        margin: 0 auto;
        text-align: center;
    }
    .success-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        animation: scaleIn 0.5s ease;
    }
    .success-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
    }
    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    @keyframes scaleIn {
        from {
            transform: scale(0);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }
    @keyframes checkmark {
        0% { stroke-dashoffset: 100; }
        100% { stroke-dashoffset: 0; }
    }
    .btn-action {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 14px 28px;
        font-weight: 600;
        font-size: 15px;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin: 8px;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(135, 35, 65, 0.3);
        color: white;
    }
    .btn-secondary {
        background: linear-gradient(135deg, #6366f1, #4f46e5);
    }
    .btn-secondary:hover {
        box-shadow: 0 8px 16px rgba(99, 102, 241, 0.3);
    }
</style>

<div class="success-container">
    <!-- Success Icon -->
    <div class="success-icon">
        <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="20 6 9 17 4 12" style="stroke-dasharray: 100; stroke-dashoffset: 0; animation: checkmark 0.5s ease 0.3s forwards;"></polyline>
        </svg>
    </div>

    <h1 style="font-size: 36px; font-weight: 800; color: #10b981; margin-bottom: 12px;">
        Payment Successful!
    </h1>
    <p style="font-size: 16px; color: #64748b; margin-bottom: 32px;">
        Thank you for your payment. Your appointment is now confirmed and paid.
    </p>

    <!-- Payment Details -->
    <div class="success-card">
        <div style="background: linear-gradient(135deg, #f0fdf4, #dcfce7); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <div style="font-size: 14px; color: #166534; margin-bottom: 8px; font-weight: 600;">
                Amount Paid
            </div>
            <div style="font-size: 42px; font-weight: 800; color: #15803d;">
                {{ App\Facades\Settings::formatPrice($payment->amount) }}
            </div>
        </div>

        <div class="detail-row">
            <span style="color: #64748b; font-weight: 500;">Payment ID</span>
            <span style="color: #1e293b; font-weight: 600;">#{{ $payment->id }}</span>
        </div>

        <div class="detail-row">
            <span style="color: #64748b; font-weight: 500;">Transaction Date</span>
            <span style="color: #1e293b; font-weight: 600;">{{ $payment->paid_at->format('F d, Y h:i A') }}</span>
        </div>

        <div class="detail-row">
            <span style="color: #64748b; font-weight: 500;">Appointment Date</span>
            <span style="color: #1e293b; font-weight: 600;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</span>
        </div>

        <div class="detail-row">
        <div class="info-row">
            <span style="color: #64748b; font-weight: 500;">Status</span>
            <span style="background: #dcfce7; color: #15803d; padding: 6px 12px; border-radius: 8px; font-weight: 600; font-size: 13px;">
                <i class="bi bi-check-circle-fill me-1"></i>Paid
            </span>
        </div>
    </div>

    <!-- Info Box -->
    <div style="background: #eff6ff; border-left: 4px solid #3b82f6; padding: 20px; border-radius: 12px; text-align: left; margin-bottom: 32px;">
        <div style="display: flex; align-items: start; gap: 12px;">
            <i class="bi bi-info-circle-fill" style="font-size: 24px; color: #3b82f6;"></i>
            <div>
                <div style="font-weight: 600; color: #1e293b; margin-bottom: 8px;">
                    What's Next?
                </div>
                <ul style="margin: 0; padding-left: 20px; color: #64748b; font-size: 14px; line-height: 1.8;">
                    <li>You'll receive a confirmation email shortly</li>
                    <li>The provider will contact you before the appointment</li>
                    <li>Please arrive 5-10 minutes early</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="margin-top: 32px;">
        <a href="{{ route('customer.bookings') }}" class="btn-action">
            <i class="bi bi-calendar-check me-2"></i>View My Bookings
        </a>
        <a href="{{ route('customer.dashboard') }}" class="btn-action btn-secondary">
            <i class="bi bi-house-door me-2"></i>Back to Dashboard
        </a>
    </div>

    <!-- Receipt Download (Optional) -->
    <div style="margin-top: 32px;">
        <a href="#" style="color: #872341; font-size: 14px; font-weight: 600; text-decoration: none;">
            <i class="bi bi-download me-1"></i>Download Receipt
        </a>
    </div>
</div>
</x-customer-dashboard>
