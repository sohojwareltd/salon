<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\WalletService;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    public function __construct(
        protected WalletService $walletService
    ) {}

    /**
     * Get provider's appointments
     */
    public function appointments(Request $request)
    {
        $provider = auth()->user()->provider;

        if (!$provider) {
            return response()->json(['message' => 'Provider profile not found'], 404);
        }

        $query = $provider->appointments()
            ->with(['user', 'service', 'payment']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('upcoming')) {
            $query->where('appointment_date', '>=', now())
                ->whereIn('status', ['pending', 'confirmed']);
        }

        $appointments = $query->latest('appointment_date')->paginate(20);

        return response()->json([
            'success' => true,
            'appointments' => $appointments,
        ]);
    }

    /**
     * Update appointment status
     */
    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        $provider = auth()->user()->provider;

        // Verify ownership
        if ($appointment->provider_id !== $provider->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
            'completed_at' => $request->status === 'completed' ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Appointment status updated successfully',
            'appointment' => $appointment->load(['customer', 'service']),
        ]);
    }

    /**
     * Get wallet balance and summary
     */
    public function wallet(Request $request)
    {
        $provider = auth()->user()->provider;

        if (!$provider) {
            return response()->json(['message' => 'Provider profile not found'], 404);
        }

        $period = $request->get('period', 'all');
        $summary = $this->walletService->getProviderEarningsSummary($provider->id, $period);

        return response()->json([
            'success' => true,
            'wallet_balance' => $provider->wallet_balance,
            'summary' => $summary,
        ]);
    }

    /**
     * Get wallet transactions
     */
    public function walletTransactions(Request $request)
    {
        $provider = auth()->user()->provider;

        if (!$provider) {
            return response()->json(['message' => 'Provider profile not found'], 404);
        }

        $transactions = $this->walletService->getProviderWalletEntries($provider->id)
            ->paginate(20);

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Get provider statistics
     */
    public function statistics()
    {
        $provider = auth()->user()->provider;

        if (!$provider) {
            return response()->json(['message' => 'Provider profile not found'], 404);
        }

        $stats = [
            'total_appointments' => $provider->appointments()->count(),
            'completed_appointments' => $provider->appointments()->where('status', 'completed')->count(),
            'pending_appointments' => $provider->appointments()->where('status', 'pending')->count(),
            'confirmed_appointments' => $provider->appointments()->where('status', 'confirmed')->count(),
            'today_appointments' => $provider->appointments()->whereDate('appointment_date', today())->count(),
            'wallet_balance' => $provider->wallet_balance,
            'average_rating' => $provider->average_rating,
            'total_reviews' => $provider->total_reviews,
            'monthly_earnings' => $this->walletService->getProviderEarningsSummary($provider->id, 'month'),
        ];

        return response()->json([
            'success' => true,
            'statistics' => $stats,
        ]);
    }

    /**
     * Get provider reviews
     */
    public function reviews()
    {
        $provider = auth()->user()->provider;

        if (!$provider) {
            return response()->json(['message' => 'Provider profile not found'], 404);
        }

        $reviews = $provider->reviews()
            ->with(['customer', 'appointment.service'])
            ->latest()
            ->paginate(15);

        return response()->json([
            'success' => true,
            'reviews' => $reviews,
        ]);
    }
}
