<x-customer-dashboard title="Payment">
<style>
    .payment-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .payment-header {
        background: linear-gradient(135deg, #872341, #BE3144);
        padding: 40px;
        border-radius: 20px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
    }
    .payment-header::before {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }
    .payment-card {
        background: white;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        margin-bottom: 24px;
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
    .info-label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }
    .info-value {
        font-size: 15px;
        color: #1e293b;
        font-weight: 600;
    }
    .service-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .total-amount {
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 2px solid #bbf7d0;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        margin: 24px 0;
    }
    .btn-pay {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 16px 32px;
        font-weight: 700;
        font-size: 16px;
        width: 100%;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
    }
    .btn-pay:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 24px rgba(16, 185, 129, 0.3);
        color: white;
    }
    .btn-pay:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .tip-btn {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px;
        font-weight: 600;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .tip-btn:hover {
        border-color: #f59e0b;
        color: #f59e0b;
        background: #fffbeb;
    }
    .tip-btn.active {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-color: #f59e0b;
    }
    .stripe-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #635bff;
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
    }
    .security-note {
        background: #f8fafc;
        border-left: 4px solid #3b82f6;
        padding: 16px;
        border-radius: 8px;
        margin-top: 24px;
    }
</style>

<div class="payment-container">
    <!-- Page Header -->
    <div class="payment-header">
        <div style="position: relative; z-index: 1;">
            <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 12px;">
                <div style="width: 64px; height: 64px; background: rgba(255,255,255,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-credit-card-fill" style="font-size: 32px; color: white;"></i>
                </div>
                <div>
                    <h2 style="font-size: 32px; font-weight: 800; color: white; margin: 0;">
                        Complete Payment
                    </h2>
                    <p style="font-size: 15px; color: rgba(255,255,255,0.9); margin: 0;">
                        Secure checkout powered by Stripe
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Details -->
    <div class="payment-card">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px; padding-bottom: 20px; border-bottom: 2px solid #f1f5f9;">
            <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #872341, #BE3144); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-calendar-check-fill" style="font-size: 24px; color: white;"></i>
            </div>
            <div>
                <h5 style="font-size: 20px; font-weight: 700; color: #1e293b; margin: 0;">
                    Appointment Details
                </h5>
                <p style="font-size: 13px; color: #64748b; margin: 0;">
                    Booking ID: #{{ $appointment->id }}
                </p>
            </div>
        </div>

        <div class="info-row">
            <span class="info-label">
                <i class="bi bi-shop me-1" style="color: #872341;"></i>Salon
            </span>
            <span class="info-value">{{ $appointment->salon->salon_name }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">
                <i class="bi bi-person-fill me-1" style="color: #872341;"></i>Provider
            </span>
            <span class="info-value">{{ $appointment->provider->name }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">
                <i class="bi bi-calendar-event me-1" style="color: #872341;"></i>Date
            </span>
            <span class="info-value">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('F d, Y') }}</span>
        </div>

        <div class="info-row">
            <span class="info-label">
                <i class="bi bi-clock-fill me-1" style="color: #872341;"></i>Time
            </span>
            <span class="info-value">{{ \Carbon\Carbon::parse($appointment->start_time)->format('h:i A') }}</span>
        </div>
    </div>

    <!-- Services -->
    <div class="payment-card">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #3b82f6, #2563eb); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-scissors" style="font-size: 20px; color: white;"></i>
            </div>
            <h5 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                Services
            </h5>
        </div>

        @foreach($appointment->services as $service)
        <div class="service-item">
            <div>
                <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">
                    {{ $service->service_name }}
                </div>
                <div style="font-size: 13px; color: #64748b;">
                    <i class="bi bi-clock me-1"></i>{{ $service->duration }} minutes
                </div>
            </div>
            <div style="font-size: 18px; font-weight: 700; color: #10b981;">
                {{ Settings::formatPrice($service->price) }}
            </div>
        </div>
        @endforeach
    </div>

    <!-- Tip Section -->
    <div class="payment-card">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
            <div style="width: 44px; height: 44px; background: linear-gradient(135deg, #f59e0b, #d97706); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                <i class="bi bi-heart-fill" style="font-size: 20px; color: white;"></i>
            </div>
            <h5 style="font-size: 18px; font-weight: 700; color: #1e293b; margin: 0;">
                Add Tip (Optional)
            </h5>
        </div>

        <p style="font-size: 14px; color: #64748b; margin-bottom: 16px;">
            Show appreciation for great service! 100% of the tip goes to your provider.
        </p>

        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-bottom: 16px;">
            <button type="button" class="tip-btn" data-tip="0" onclick="selectTip(0)">
                No Tip
            </button>
            <button type="button" class="tip-btn" data-tip="5" onclick="selectTip(5)">
                $5
            </button>
            <button type="button" class="tip-btn" data-tip="10" onclick="selectTip(10)">
                $10
            </button>
            <button type="button" class="tip-btn" data-tip="15" onclick="selectTip(15)">
                $15
            </button>
        </div>

        <div style="display: flex; align-items: center; gap: 12px;">
            <label style="font-size: 14px; font-weight: 600; color: #1e293b;">Custom Amount:</label>
            <input type="number" id="customTip" class="form-control-settings" placeholder="0.00" 
                   min="0" step="0.01" onchange="selectCustomTip(this.value)"
                   style="border: 2px solid #e2e8f0; border-radius: 12px; padding: 10px 14px; font-size: 14px; max-width: 150px;">
        </div>
    </div>

    <!-- Total Amount -->
    <div class="total-amount">
        <div style="margin-bottom: 16px; padding-bottom: 16px; border-bottom: 2px dashed #bbf7d0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 15px; color: #166534; font-weight: 500;">Service Amount</span>
                <span style="font-size: 18px; color: #15803d; font-weight: 700;" id="serviceAmount">{{ Settings::formatPrice($appointment->total_amount) }}</span>
            </div>
            <div style="display: flex; justify-content: space-between;">
                <span style="font-size: 15px; color: #166534; font-weight: 500;">Tip</span>
                <span style="font-size: 18px; color: #f59e0b; font-weight: 700;" id="tipAmount">$0.00</span>
            </div>
        </div>
        <div style="font-size: 15px; color: #166534; font-weight: 600; margin-bottom: 8px;">
            Total Amount to Pay
        </div>
        <div style="font-size: 48px; font-weight: 800; color: #15803d;" id="totalAmount">
            {{ Settings::formatPrice($appointment->total_amount) }}
        </div>
        <div style="font-size: 13px; color: #16a34a; margin-top: 4px;">
            USD (US Dollar)
        </div>
    </div>

    <!-- Pay Button -->
    <button type="button" id="payButton" class="btn-pay">
        <i class="bi bi-lock-fill" style="font-size: 20px;"></i>
        <span>Pay Securely with Stripe</span>
        <i class="bi bi-arrow-right" style="font-size: 20px;"></i>
    </button>

    <!-- Security Note -->
    <div class="security-note">
        <div style="display: flex; align-items: start; gap: 12px;">
            <i class="bi bi-shield-check" style="font-size: 24px; color: #3b82f6;"></i>
            <div>
                <div style="font-weight: 600; color: #1e293b; margin-bottom: 4px;">
                    Secure Payment
                </div>
                <div style="font-size: 13px; color: #64748b; line-height: 1.5;">
                    Your payment is processed securely through Stripe. We don't store your card details. 
                    All transactions are encrypted and PCI DSS compliant.
                </div>
            </div>
        </div>
    </div>

    <!-- Powered by Stripe -->
    <div style="text-align: center; margin-top: 24px;">
        <span class="stripe-badge">
            <i class="bi bi-shield-fill-check"></i>
            Powered by Stripe
        </span>
    </div>
</div>

<script>
let selectedTip = 0;
const serviceAmount = {{ $appointment->total_amount }};

function selectTip(amount) {
    selectedTip = amount;
    
    // Update active button
    document.querySelectorAll('.tip-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Clear custom input
    document.getElementById('customTip').value = '';
    
    updateTotal();
}

function selectCustomTip(amount) {
    selectedTip = parseFloat(amount) || 0;
    
    // Remove active from preset buttons
    document.querySelectorAll('.tip-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    
    updateTotal();
}

function updateTotal() {
    const tipElement = document.getElementById('tipAmount');
    const totalElement = document.getElementById('totalAmount');
    
    tipElement.textContent = '$' + selectedTip.toFixed(2);
    const total = serviceAmount + selectedTip;
    totalElement.textContent = '$' + total.toFixed(2);
}

document.getElementById('payButton').addEventListener('click', async function() {
    const button = this;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i> <span>Processing...</span>';

    try {
        const response = await fetch('{{ route("customer.payment.checkout", $appointment->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                tip_amount: selectedTip
            })
        });

        const data = await response.json();

        if (data.url) {
            // Redirect to Stripe Checkout
            window.location.href = data.url;
        } else {
            throw new Error('Failed to create checkout session');
        }
    } catch (error) {
        alert('Payment error: ' + error.message);
        button.disabled = false;
        button.innerHTML = '<i class="bi bi-lock-fill"></i> <span>Pay Securely with Stripe</span> <i class="bi bi-arrow-right"></i>';
    }
});
</script>
</x-customer-dashboard>
