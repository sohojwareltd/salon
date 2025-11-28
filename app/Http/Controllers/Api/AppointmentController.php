<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Provider;
use App\Services\AdvancedScheduleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function __construct(
        protected AdvancedScheduleService $scheduleService
    ) {}

    /**
     * Get available time slots for a provider on a specific date
     */
    public function availableSlots(Request $request, Provider $provider)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
            'service_ids' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $serviceIds = explode(',', $request->service_ids);
        $services = \App\Models\Service::whereIn('id', $serviceIds)->get();
        
        if ($services->isEmpty()) {
            return response()->json(['errors' => ['service_ids' => 'Invalid service IDs']], 422);
        }

        $slots = $this->scheduleService->getAvailableSlotsForMultipleServices(
            $provider,
            $services,
            $request->date
        );

        return response()->json([
            'success' => true,
            'data' => [
                'date' => $request->date,
                'slots' => $slots->values()->toArray(),
                'total_duration' => $services->sum('duration'),
            ],
        ]);
    }

    /**
     * Book an appointment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'required|exists:services,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $provider = Provider::findOrFail($request->provider_id);
        $services = \App\Models\Service::whereIn('id', $request->service_ids)->get();

        try {
            $appointment = $this->scheduleService->bookAppointmentWithMultipleServices(
                $provider,
                auth()->id(),
                $services,
                $request->appointment_date,
                $request->start_time,
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Appointment booked successfully',
                'appointment' => $appointment->load(['provider.user', 'services', 'salon']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get customer's appointments
     */
    public function index(Request $request)
    {
        $query = auth()->user()->appointments()
            ->with(['salon', 'provider.user', 'service', 'payment']);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
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
     * Get specific appointment details
     */
    public function show(Appointment $appointment)
    {
        // Verify ownership
        if ($appointment->customer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'appointment' => $appointment->load([
                'salon',
                'provider.user',
                'service',
                'payment',
                'review',
            ]),
        ]);
    }

    /**
     * Cancel an appointment
     */
    public function cancel(Appointment $appointment)
    {
        // Verify ownership
        if ($appointment->customer_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Check if cancellable
        if (!in_array($appointment->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'This appointment cannot be cancelled',
            ], 400);
        }

        $appointment->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Appointment cancelled successfully',
            'appointment' => $appointment,
        ]);
    }
}
