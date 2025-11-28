<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Provider;
use App\Models\ProviderException;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AdvancedScheduleService
{
    /**
     * Generate available time slots for a provider on a given date
     */
    public function getAvailableSlots(Provider $provider, Service $service, string $date): Collection
    {
        $provider->load(['schedules', 'exceptions']);
        $requestedDate = Carbon::parse($date);
        $weekday = $requestedDate->dayOfWeek; // 0 = Sunday, 6 = Saturday

        // Check provider's schedule for this weekday
        $providerSchedule = $provider->schedules()->where('weekday', $weekday)->first();
        
        if (!$providerSchedule || $providerSchedule->is_off) {
            return collect([]);
        }

        // Check provider exceptions
        $providerException = $provider->exceptions()->whereDate('date', $date)->first();
        if ($providerException && $providerException->is_off) {
            return collect([]);
        }

        // Determine working hours
        $openTime = $providerSchedule->start_time ?? '09:00:00';
        $closeTime = $providerSchedule->end_time ?? '18:00:00';

        // If provider has custom hours for this exception date
        if ($providerException && !$providerException->is_off) {
            $openTime = $providerException->start_time;
            $closeTime = $providerException->end_time;
        }

        $openingTime = Carbon::parse($date . ' ' . $openTime);
        $closingTime = Carbon::parse($date . ' ' . $closeTime);

        // Generate all possible slots
        $slots = collect();
        $currentSlot = $openingTime->copy();
        $serviceDuration = $service->duration;

        while ($currentSlot->copy()->addMinutes($serviceDuration)->lte($closingTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($serviceDuration);

            // Skip if slot overlaps with provider's break time
            if ($provider->break_start && $provider->break_end) {
                $breakStart = Carbon::parse($date . ' ' . $provider->break_start);
                $breakEnd = Carbon::parse($date . ' ' . $provider->break_end);

                if ($currentSlot->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                    $currentSlot->addMinutes(30);
                    continue;
                }
            }

            // Check if slot is available (not booked)
            $isBooked = Appointment::where('provider_id', $provider->id)
                ->whereDate('appointment_date', $date)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->where(function ($query) use ($currentSlot, $slotEnd) {
                    $query->whereBetween('start_time', [$currentSlot->format('H:i:s'), $slotEnd->format('H:i:s')])
                        ->orWhereBetween('end_time', [$currentSlot->format('H:i:s'), $slotEnd->format('H:i:s')])
                        ->orWhere(function ($q) use ($currentSlot, $slotEnd) {
                            $q->where('start_time', '<=', $currentSlot->format('H:i:s'))
                              ->where('end_time', '>=', $slotEnd->format('H:i:s'));
                        });
                })
                ->exists();

            if (!$isBooked) {
                $slots->push($currentSlot->format('H:i:s'));
            }

            // Move to next slot (30-minute intervals)
            $currentSlot->addMinutes(30);
        }

        return $slots;
    }

    /**
     * Get available slots for multiple services combined
     */
    public function getAvailableSlotsForMultipleServices(Provider $provider, Collection $services, string $date): Collection
    {
        $provider->load(['schedules', 'exceptions']);
        $requestedDate = Carbon::parse($date);
        $weekday = $requestedDate->dayOfWeek;

        // Check provider's schedule for this weekday
        $providerSchedule = $provider->schedules()->where('weekday', $weekday)->first();
        
        if (!$providerSchedule || $providerSchedule->is_off) {
            return collect([]);
        }

        // Check provider exceptions
        $providerException = $provider->exceptions()->whereDate('date', $date)->first();
        if ($providerException && $providerException->is_off) {
            return collect([]);
        }

        // Determine working hours
        $openTime = $providerSchedule->start_time ?? '09:00:00';
        $closeTime = $providerSchedule->end_time ?? '18:00:00';

        if ($providerException && !$providerException->is_off) {
            $openTime = $providerException->start_time;
            $closeTime = $providerException->end_time;
        }

        $openingTime = Carbon::parse($date . ' ' . $openTime);
        $closingTime = Carbon::parse($date . ' ' . $closeTime);

        // Calculate total duration (all services + buffer time)
        $totalDuration = $services->sum('duration');
        $bufferTime = $provider->buffer_time ?? 0;
        $totalDurationWithBuffer = $totalDuration + $bufferTime;

        // Generate all possible slots
        $slots = collect();
        $currentSlot = $openingTime->copy();

        while ($currentSlot->copy()->addMinutes($totalDurationWithBuffer)->lte($closingTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($totalDurationWithBuffer);

            // Skip if slot overlaps with provider's break time
            if ($provider->break_start && $provider->break_end) {
                $breakStart = Carbon::parse($date . ' ' . $provider->break_start);
                $breakEnd = Carbon::parse($date . ' ' . $provider->break_end);

                if ($currentSlot->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                    $currentSlot->addMinutes(30);
                    continue;
                }
            }

            // Check if slot is available (not booked) - IMPORTANT: Include pending status
            $isBooked = Appointment::where('provider_id', $provider->id)
                ->whereDate('appointment_date', $date)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress']) // Pending blocks the slot too
                ->where(function ($query) use ($currentSlot, $slotEnd) {
                    $query->whereBetween('start_time', [$currentSlot->format('H:i:s'), $slotEnd->format('H:i:s')])
                        ->orWhereBetween('end_time', [$currentSlot->format('H:i:s'), $slotEnd->format('H:i:s')])
                        ->orWhere(function ($q) use ($currentSlot, $slotEnd) {
                            $q->where('start_time', '<=', $currentSlot->format('H:i:s'))
                              ->where('end_time', '>=', $slotEnd->format('H:i:s'));
                        });
                })
                ->exists();

            if (!$isBooked) {
                $slots->push($currentSlot->format('H:i'));
            }

            // Move to next slot (30-minute intervals)
            $currentSlot->addMinutes(30);
        }

        return $slots;
    }

    /**
     * Book an appointment with multiple services
     */
    public function bookAppointmentWithMultipleServices(Provider $provider, int $userId, Collection $services, string $date, string $startTime, ?string $notes = null): Appointment
    {
        $startTimeCarbon = Carbon::parse($date . ' ' . $startTime);
        $totalDuration = $services->sum('duration');
        $bufferTime = $provider->buffer_time ?? 0;
        $endTime = $startTimeCarbon->copy()->addMinutes($totalDuration + $bufferTime);

        // Double-check slot is still available
        $isBooked = Appointment::where('provider_id', $provider->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->where(function ($query) use ($startTimeCarbon, $endTime) {
                $query->whereBetween('start_time', [$startTimeCarbon->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhereBetween('end_time', [$startTimeCarbon->format('H:i:s'), $endTime->format('H:i:s')])
                    ->orWhere(function ($q) use ($startTimeCarbon, $endTime) {
                        $q->where('start_time', '<=', $startTimeCarbon->format('H:i:s'))
                          ->where('end_time', '>=', $endTime->format('H:i:s'));
                    });
            })
            ->exists();

        if ($isBooked) {
            throw new \Exception('This time slot is no longer available');
        }

        $appointment = Appointment::create([
            'customer_id' => $userId,
            'provider_id' => $provider->id,
            'service_id' => $services->first()->id, // Primary service
            'appointment_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime->format('H:i:s'),
            'status' => 'pending',
            'payment_status' => 'pending',
            'notes' => $notes,
            'total_amount' => $services->sum('price'),
        ]);

        // Attach all services to the appointment
        $appointment->services()->attach($services->pluck('id'));

        return $appointment;
    }

    /**
     * Book an appointment slot (legacy single service)
     */
    public function bookAppointment($user, int $providerId, int $serviceId, string $date, string $startTime, ?string $notes = null): Appointment
    {
        $service = Service::findOrFail($serviceId);
        $startTimeCarbon = Carbon::parse($date . ' ' . $startTime);
        $endTime = $startTimeCarbon->copy()->addMinutes($service->duration);

        return Appointment::create([
            'user_id' => $user->id,
            'provider_id' => $providerId,
            'service_id' => $serviceId,
            'appointment_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime->format('H:i:s'),
            'status' => 'pending',
            'payment_status' => 'pending',
            'notes' => $notes,
        ]);
    }
}
