<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Models\Service;
use App\Services\AppointmentSlotService;
use Illuminate\Http\Request;

class ProviderApiController extends Controller
{
    protected $slotService;

    public function __construct(AppointmentSlotService $slotService)
    {
        $this->slotService = $slotService;
    }

    /**
     * Display a listing of providers
     */
    public function index(Request $request)
    {
        $query = Provider::where('is_active', true)
            ->with(['salon', 'services']);

        if ($request->filled('salon_id')) {
            $query->where('salon_id', $request->salon_id);
        }

        $providers = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $providers,
        ]);
    }

    /**
     * Display the specified provider
     */
    public function show(string $id)
    {
        $provider = Provider::with(['salon', 'services', 'reviews.user'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $provider,
        ]);
    }

    /**
     * Get available slots for a provider
     */
    public function availableSlots(Request $request, string $id)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date|after_or_equal:today',
        ]);

        $provider = Provider::findOrFail($id);
        $service = Service::findOrFail($request->service_id);

        $slots = $this->slotService->getAvailableSlots($provider, $service, $request->date);

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $request->date,
                'slots' => $slots,
            ],
        ]);
    }

    /**
     * Get provider reviews
     */
    public function reviews(string $id)
    {
        $provider = Provider::findOrFail($id);
        $reviews = $provider->reviews()
            ->with(['user', 'appointment.service'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }
}
