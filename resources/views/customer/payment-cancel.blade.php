<x-customer-dashboard title="Payment Cancelled">
<style>
    .cancel-container {
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }
    .cancel-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        animation: scaleIn 0.5s ease;
    }
    .cancel-card {
        background: white;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
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
        background: linear-gradient(135deg, #6b7280, #4b5563);
    }
    .btn-secondary:hover {
        box-shadow: 0 8px 16px rgba(107, 114, 128, 0.3);
    }
</style>

<div class="cancel-container">
    <!-- Cancel Icon -->
    <div class="cancel-icon">
        <i class="bi bi-x-circle-fill" style="font-size: 64px; color: white;"></i>
    </div>

    <h1 style="font-size: 36px; font-weight: 800; color: #d97706; margin-bottom: 12px;">
        Payment Cancelled
    </h1>
    <p style="font-size: 16px; color: #64748b; margin-bottom: 32px;">
        Your payment was cancelled. No charges have been made to your card.
    </p>

    <!-- Info Card -->
    <div class="cancel-card">
        <div style="background: linear-gradient(135deg, #fef3c7, #fde68a); border-radius: 16px; padding: 24px; margin-bottom: 24px;">
            <i class="bi bi-info-circle-fill" style="font-size: 42px; color: #d97706;"></i>
            <div style="margin-top: 16px; font-size: 15px; color: #78350f; line-height: 1.6;">
                Your appointment is still <strong>confirmed</strong> but awaiting payment.
                <br>You can complete the payment anytime before your appointment.
            </div>
        </div>

        <div style="background: #f8fafc; border-radius: 12px; padding: 20px; text-align: left;">
            <div style="font-weight: 600; color: #1e293b; margin-bottom: 12px;">
                Appointment Details
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #64748b;">Salon:</span>
                <span style="color: #1e293b; font-weight: 600;">{{ $appointment->salon->salon_name }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="color: #64748b;">Date:</span>
                <span style="color: #1e293b; font-weight: 600;">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="color: #64748b;">Amount:</span>
                <span style="color: #10b981; font-weight: 700; font-size: 18px;">{{ Settings::formatPrice($appointment->total_amount) }}</span>
            </div>
        </div>
    </div>

    <!-- Warning Box -->
    <div style="background: #fef2f2; border-left: 4px solid #ef4444; padding: 20px; border-radius: 12px; text-align: left; margin-bottom: 32px;">
        <div style="display: flex; align-items: start; gap: 12px;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size: 24px; color: #ef4444;"></i>
            <div>
                <div style="font-weight: 600; color: #991b1b; margin-bottom: 8px;">
                    Important Notice
                </div>
                <div style="color: #7f1d1d; font-size: 14px; line-height: 1.6;">
                    Please complete the payment before your appointment date to avoid cancellation.
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div style="margin-top: 32px;">
        <a href="{{ route('customer.payment.show', $appointment->id) }}" class="btn-action">
            <i class="bi bi-credit-card me-2"></i>Try Payment Again
        </a>
        <a href="{{ route('customer.bookings') }}" class="btn-action btn-secondary">
            <i class="bi bi-calendar-check me-2"></i>View Bookings
        </a>
    </div>

    <!-- Support Link -->
    <div style="margin-top: 32px;">
        <p style="color: #64748b; font-size: 14px;">
            Having trouble with payment?
            <a href="{{ route('contact') }}" style="color: #872341; font-weight: 600; text-decoration: none;">
                Contact Support
            </a>
        </p>
    </div>
</div>
</x-customer-dashboard>
