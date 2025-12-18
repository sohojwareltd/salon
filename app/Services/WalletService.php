<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Provider;
use App\Models\ProviderWalletEntry;

class WalletService
{
    /**
     * Create wallet entry after successful payment
     */
    public function createWalletEntry(Payment $payment): ProviderWalletEntry
    {
        $appointment = $payment->appointment()->with(['provider', 'salon', 'service'])->first();
        $provider = $appointment->provider;
        $salon = $appointment->salon;
        $service = $appointment->service;

        $serviceAmount = $service->price;
        $tipAmount = $payment->tip_amount ?? 0;

        // Calculate commissions
        $salonCommissionRate = $salon->commission_percentage / 100;
        $providerCommissionRate = $provider->commission_percentage / 100;

        $salonAmount = $serviceAmount * $salonCommissionRate;
        $providerAmount = $serviceAmount * $providerCommissionRate;
        $totalProviderAmount = $providerAmount + $tipAmount;

        // Create wallet entry
        $walletEntry = ProviderWalletEntry::create([
            'provider_id' => $provider->id,
            'appointment_id' => $appointment->id,
            'payment_id' => $payment->id,
            'service_amount' => $serviceAmount,
            'salon_amount' => $salonAmount,
            'provider_amount' => $providerAmount,
            'tips_amount' => $tipAmount,
            'total_provider_amount' => $totalProviderAmount,
            'type' => 'earning',
            'notes' => "Payment for appointment #{$appointment->id}",
        ]);

        // Update provider wallet balance
        $provider->increment('wallet_balance', $totalProviderAmount);

        return $walletEntry;
    }

    /**
     * Get provider wallet balance
     */
    public function getProviderBalance(Provider $provider): float
    {
        return (float) $provider->wallet_balance;
    }

    /**
     * Get provider wallet entries
     */
    public function getProviderWalletEntries(Provider $provider, int $limit = 50)
    {
        return $provider->walletEntries()
            ->with(['appointment.service', 'payment'])
            ->latest()
            ->paginate($limit);
    }

    /**
     * Get provider earnings summary
     */
    public function getProviderEarningsSummary(Provider $provider): array
    {
        $totalEarnings = $provider->walletEntries()
            ->where('type', 'earning')
            ->sum('total_provider_amount');

        $totalTips = $provider->walletEntries()
            ->where('type', 'earning')
            ->sum('tips_amount');

        $totalWithdrawals = $provider->walletEntries()
            ->where('type', 'withdrawal')
            ->sum('total_provider_amount');

        $completedBookings = $provider->appointments()
            ->where('status', 'completed')
            ->where('payment_status', 'paid')
            ->count();

        return [
            'total_earnings' => $totalEarnings,
            'total_tips' => $totalTips,
            'total_withdrawals' => $totalWithdrawals,
            'current_balance' => $provider->wallet_balance,
            'completed_bookings' => $completedBookings,
        ];
    }

    /**
     * Create wallet entry from appointment (without payment)
     * Used when appointment is completed
     */
    public function createWalletEntryFromAppointment(Appointment $appointment): ProviderWalletEntry
    {
        $appointment->load(['provider', 'services', 'payment']);
        $provider = $appointment->provider;
        $payment = $appointment->payment;

        // Calculate total service amount from all services
        $serviceAmount = $appointment->total_amount ?? $appointment->services->sum('price');

        // Get tips from payment if exists
        $tipsAmount = $payment?->tip_amount ?? 0;

        // Calculate commissions based on provider's percentage
        $providerCommissionRate = $provider->commission_percentage / 100;
        $providerAmount = $serviceAmount * $providerCommissionRate;
        
        // Salon gets the rest
        $salonAmount = $serviceAmount - $providerAmount;
        
        // Total provider amount includes tips
        $totalProviderAmount = $providerAmount + $tipsAmount;

        // Build notes message
        $serviceCount = $appointment->services->count();
        $notes = "Earnings from appointment #{$appointment->id}";
        
        if ($serviceCount > 0) {
            $notes .= " with " . $serviceCount . " service" . ($serviceCount > 1 ? "s" : "");
        }
        
        if ($tipsAmount > 0) {
            $notes .= " + " . Settings::formatPrice($tipsAmount, false) . " tips";
        }

        // Create wallet entry
        $walletEntry = ProviderWalletEntry::create([
            'provider_id' => $provider->id,
            'appointment_id' => $appointment->id,
            'payment_id' => $payment?->id,
            'service_amount' => $serviceAmount,
            'salon_amount' => $salonAmount,
            'provider_amount' => $providerAmount,
            'tips_amount' => $tipsAmount,
            'total_provider_amount' => $totalProviderAmount,
            'type' => 'earning',
            'notes' => $notes,
        ]);

        // Update provider wallet balance
        $provider->increment('wallet_balance', $totalProviderAmount);

        return $walletEntry;
    }

    /**
     * Get salon earnings summary
     */
    public function getSalonEarningsSummary($salonId): array
    {
        $totalSalonEarnings = ProviderWalletEntry::whereHas('provider', function ($query) use ($salonId) {
            $query->where('salon_id', $salonId);
        })->sum('salon_amount');

        $totalProviderEarnings = ProviderWalletEntry::whereHas('provider', function ($query) use ($salonId) {
            $query->where('salon_id', $salonId);
        })->sum('provider_amount');

        $totalTips = ProviderWalletEntry::whereHas('provider', function ($query) use ($salonId) {
            $query->where('salon_id', $salonId);
        })->sum('tips_amount');

        $totalRevenue = $totalSalonEarnings + $totalProviderEarnings + $totalTips;

        return [
            'total_revenue' => $totalRevenue,
            'salon_earnings' => $totalSalonEarnings,
            'provider_earnings' => $totalProviderEarnings,
            'total_tips' => $totalTips,
        ];
    }
}
