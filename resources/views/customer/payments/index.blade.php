<x-customer-dashboard title="Payment History">
<div class="mb-4">
    <h2 class="fw-bold">Payment History</h2>
    <p class="text-muted">View all your completed payments</p>
</div>

<!-- Payments List -->
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Transaction Date</th>
                        <th>Salon</th>
                        <th>Service</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $appointment)
                        <tr>
                            <td>
                                <div class="fw-semibold">{{ $appointment->appointment_date->format('M d, Y') }}</div>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($appointment->start_time)->format('g:i A') }}</small>
                            </td>
                            <td>{{ $appointment->salon->name }}</td>
                            <td>{{ $appointment->service->name }}</td>
                            <td class="fw-bold">{{ App\Facades\Settings::formatPrice($appointment->service->price) }}</td>
                            <td>
                                @if($appointment->payment)
                                    {{ ucfirst($appointment->payment->payment_method) }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle"></i> Paid
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-wallet2 text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-3">No payment history found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<div class="mt-4">
    {{ $payments->links() }}
</div>
</x-customer-dashboard>
