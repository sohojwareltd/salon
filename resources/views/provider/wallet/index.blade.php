@extends('layouts.provider-dashboard')

@section('content')
<style>
    .wallet-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 28px;
    }
    
    .wallet-title {
        font-size: 28px;
        font-weight: 700;
        color: #09122C;
        margin: 0;
    }
    
    .withdraw-btn {
        background: linear-gradient(135deg, #872341, #BE3144);
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        color: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .withdraw-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(135, 35, 65, 0.3);
    }
    
    .summary-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    
    .summary-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }
    
    .summary-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }
    
    .summary-card.balance::before {
        background: linear-gradient(180deg, #10B981, #059669);
    }
    
    .summary-card.total::before {
        background: linear-gradient(180deg, #3B82F6, #2563EB);
    }
    
    .summary-card.month::before {
        background: linear-gradient(180deg, #872341, #BE3144);
    }
    
    .summary-card.pending::before {
        background: linear-gradient(180deg, #F59E0B, #D97706);
    }
    
    .summary-icon {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 16px;
    }
    
    .summary-card.balance .summary-icon {
        background: linear-gradient(135deg, #10B981, #059669);
        color: #fff;
    }
    
    .summary-card.total .summary-icon {
        background: linear-gradient(135deg, #3B82F6, #2563EB);
        color: #fff;
    }
    
    .summary-card.month .summary-icon {
        background: linear-gradient(135deg, #872341, #BE3144);
        color: #fff;
    }
    
    .summary-card.pending .summary-icon {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        color: #fff;
    }
    
    .summary-label {
        font-size: 13px;
        color: #6b7280;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }
    
    .summary-value {
        font-size: 32px;
        font-weight: 700;
        color: #09122C;
        margin-bottom: 4px;
        line-height: 1;
    }
    
    .summary-detail {
        font-size: 13px;
        color: #9ca3af;
    }
    
    .chart-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        margin-bottom: 28px;
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
    
    .chart-container {
        position: relative;
        height: 300px;
    }
    
    .transactions-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }
    
    .transactions-header {
        padding: 20px 24px;
        border-bottom: 2px solid #f3f4f6;
    }
    
    .transactions-header h5 {
        font-size: 18px;
        font-weight: 600;
        color: #09122C;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .transactions-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .transactions-table thead th {
        background: #f9fafb;
        padding: 16px 24px;
        font-size: 13px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e5e7eb;
    }
    
    .transactions-table tbody td {
        padding: 20px 24px;
        border-bottom: 1px solid #f3f4f6;
        font-size: 14px;
        color: #374151;
    }
    
    .transactions-table tbody tr {
        transition: all 0.2s ease;
    }
    
    .transactions-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .transaction-date {
        font-weight: 500;
        color: #09122C;
        font-size: 14px;
    }
    
    .transaction-time {
        font-size: 13px;
        color: #9ca3af;
        margin-top: 2px;
    }
    
    .transaction-type {
        display: inline-flex;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .transaction-type.credit {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .transaction-type.debit {
        background: #FEE2E2;
        color: #991B1B;
    }
    
    .transaction-description {
        font-weight: 500;
        color: #374151;
    }
    
    .transaction-amount {
        font-size: 16px;
        font-weight: 700;
    }
    
    .transaction-amount.credit {
        color: #10B981;
    }
    
    .transaction-amount.debit {
        color: #EF4444;
    }
    
    .transaction-balance {
        font-size: 15px;
        font-weight: 600;
        color: #09122C;
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
        padding: 20px 24px;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
    }
    
    @media (max-width: 768px) {
        .wallet-header {
            flex-direction: column;
            align-items: stretch;
            gap: 16px;
        }
        
        .summary-cards {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="wallet-header">
    <h1 class="wallet-title">
        <i class="bi bi-wallet2 me-2"></i>My Wallet
    </h1>
    <button class="withdraw-btn" onclick="alert('Withdrawal feature coming soon!')">
        <i class="bi bi-cash-coin"></i>
        Withdraw Funds
    </button>
</div>

<!-- Summary Cards -->
<div class="summary-cards">
    <div class="summary-card balance">
        <div class="summary-icon">
            <i class="bi bi-wallet2"></i>
        </div>
        <div class="summary-label">Current Balance</div>
        <div class="summary-value">{{ App\Facades\Settings::formatPrice($summary['current_balance'] ?? 0, false) }}</div>
        <div class="summary-detail">Available to withdraw</div>
    </div>
    
    <div class="summary-card total">
        <div class="summary-icon">
            <i class="bi bi-graph-up-arrow"></i>
        </div>
        <div class="summary-label">Total Earnings</div>
        <div class="summary-value">{{ App\Facades\Settings::formatPrice($summary['total_earnings'] ?? 0, false) }}</div>
        <div class="summary-detail">All time earnings</div>
    </div>
    
    <div class="summary-card month">
        <div class="summary-icon">
            <i class="bi bi-calendar-check"></i>
        </div>
        <div class="summary-label">Total Tips</div>
        <div class="summary-value">{{ App\Facades\Settings::formatPrice($summary['total_tips'] ?? 0, false) }}</div>
        <div class="summary-detail">Tips received</div>
    </div>
    
    <div class="summary-card pending">
        <div class="summary-icon">
            <i class="bi bi-check-circle"></i>
        </div>
        <div class="summary-label">Completed Bookings</div>
        <div class="summary-value">{{ number_format($summary['completed_bookings'] ?? 0, 0) }}</div>
        <div class="summary-detail">Total completed</div>
    </div>
</div>

<!-- Monthly Trend Chart -->
<div class="chart-card">
    <h5>
        <i class="bi bi-graph-up"></i>
        Earnings Trend (Last 6 Months)
    </h5>
    <div class="chart-container">
        <canvas id="monthlyTrendChart"></canvas>
    </div>
</div>

<!-- Transaction History -->
<div class="transactions-card">
    <div class="transactions-header">
        <h5>
            <i class="bi bi-clock-history"></i>
            Transaction History
        </h5>
    </div>
    
    <div style="overflow-x: auto;">
        <table class="transactions-table">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @forelse($walletEntries as $entry)
                <tr>
                    <td>
                        <div class="transaction-date">{{ $entry->created_at->format('M d, Y') }}</div>
                        <div class="transaction-time">{{ $entry->created_at->format('g:i A') }}</div>
                    </td>
                    <td>
                        <span class="transaction-type {{ $entry->type === 'earning' ? 'credit' : 'debit' }}">
                            <i class="bi bi-{{ $entry->type === 'earning' ? 'arrow-down-circle' : 'arrow-up-circle' }} me-1"></i>
                            {{ ucfirst($entry->type) }}
                        </span>
                    </td>
                    <td>
                        <div class="transaction-description">{{ $entry->notes ?? 'Transaction' }}</div>
                    </td>
                    <td style="text-align: right;">
                        <span class="transaction-amount {{ $entry->type === 'earning' ? 'credit' : 'debit' }}">
                            {{ $entry->type === 'earning' ? '+' : '-' }}{{ App\Facades\Settings::formatPrice($entry->total_provider_amount, false) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">
                        <div class="empty-state">
                            <div class="empty-icon">
                                <i class="bi bi-wallet2"></i>
                            </div>
                            <p class="empty-text">No transactions yet</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($walletEntries->hasPages())
    <div class="pagination-wrapper">
        {{ $walletEntries->links() }}
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('monthlyTrendChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlyTrend->pluck('month')) !!},
            datasets: [{
                label: 'Monthly Earnings ({{ App\Facades\Settings::currency() }})',
                data: {!! json_encode($monthlyTrend->pluck('total')) !!},
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
                            return 'Earnings: {{ App\Facades\Settings::currency() }}' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '{{ App\Facades\Settings::currency() }}' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
