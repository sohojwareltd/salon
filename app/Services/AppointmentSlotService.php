<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Provider;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AppointmentSlotService
{
    /**
     * Generate available time slots for a provider on a given date
     *
     * @param Provider $provider
     * @param Service $service
     * @param string $date
     * @return Collection
     */
    public function getAvailableSlots(Provider $provider, Service $service, string $date): Collection
    {
        $requestedDate = Carbon::parse($date);
        $weekday = $requestedDate->dayOfWeek;
        
        // Check provider's schedule for this weekday
        $providerSchedule = $provider->schedules()->where('weekday', $weekday)->first();
        
        if (!$providerSchedule || $providerSchedule->is_off) {
            return collect([]);
        }
        
        // Get provider working hours
        $openingTime = Carbon::parse($date . ' ' . ($providerSchedule->start_time ?? '09:00:00'));
        $closingTime = Carbon::parse($date . ' ' . ($providerSchedule->end_time ?? '18:00:00'));
        
        // Generate all possible slots
        $slots = collect();
        $currentSlot = $openingTime->copy();
        
        while ($currentSlot->copy()->addMinutes($service->duration)->lte($closingTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($service->duration);
            
            // Check if slot overlaps with provider's break time
            if ($provider->break_start && $provider->break_end) {
                $breakStart = Carbon::parse($date . ' ' . $provider->break_start);
                $breakEnd = Carbon::parse($date . ' ' . $provider->break_end);
                
                // Skip if slot overlaps with break
                if ($currentSlot->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                    $currentSlot->addMinutes(30);
                    continue;
                }
            }
            
            // Check if slot is available (not booked)
            $isBooked = Appointment::where('provider_id', $provider->id)
                ->whereDate('appointment_date', $date)
                ->where('status', '!=', 'cancelled')
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
        $provider->load(['schedules']);
        $requestedDate = Carbon::parse($date);
        $weekday = $requestedDate->dayOfWeek;

        // Check provider's schedule for this weekday
        $providerSchedule = $provider->schedules()->where('weekday', $weekday)->first();
        
        if (!$providerSchedule || $providerSchedule->is_off) {
            return collect([]);
        }

        $openTime = $providerSchedule->start_time ?? '09:00:00';
        $closeTime = $providerSchedule->end_time ?? '18:00:00';

        $openingTime = Carbon::parse($date . ' ' . $openTime);
        $closingTime = Carbon::parse($date . ' ' . $closeTime);

        // Calculate total duration (all services + buffer time)
        $totalDuration = $services->sum('duration');
        $bufferTime = $provider->buffer_time ?? 0;
        $totalDurationWithBuffer = $totalDuration + $bufferTime;

        // Generate all possible slots
        $slots = collect();
        $currentSlot = $openingTime->copy();

        while ($currentSlot->copy()->addMinutes($totalDuration)->lte($closingTime)) {
            $slotEnd = $currentSlot->copy()->addMinutes($totalDuration);

            // Check if slot overlaps with provider's break time
            $overlapsBreak = false;
            if ($provider->break_start && $provider->break_end) {
                $breakStart = Carbon::parse($date . ' ' . $provider->break_start);
                $breakEnd = Carbon::parse($date . ' ' . $provider->break_end);

                // Check if ANY part of the appointment (including buffer) overlaps with break
                if ($currentSlot->lt($breakEnd) && $slotEnd->gt($breakStart)) {
                    $overlapsBreak = true;
                }
            }

            // Skip this slot if it overlaps with break time
            if ($overlapsBreak) {
                $currentSlot->addMinutes(30);
                continue;
            }

            // Check if slot is available - IMPORTANT: Include pending status
            // We need to check if there's enough time including buffer for next appointment
            $slotEndWithBuffer = $currentSlot->copy()->addMinutes($totalDuration + $bufferTime);
            
            $isBooked = Appointment::where('provider_id', $provider->id)
                ->whereDate('appointment_date', $date)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->where(function ($query) use ($currentSlot, $slotEndWithBuffer) {
                    $query->whereBetween('start_time', [$currentSlot->format('H:i:s'), $slotEndWithBuffer->format('H:i:s')])
                        ->orWhereBetween('end_time', [$currentSlot->format('H:i:s'), $slotEndWithBuffer->format('H:i:s')])
                        ->orWhere(function ($q) use ($currentSlot, $slotEndWithBuffer) {
                            $q->where('start_time', '<=', $currentSlot->format('H:i:s'))
                              ->where('end_time', '>=', $slotEndWithBuffer->format('H:i:s'));
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
        
        // End time is service duration only (customer visible time)
        $serviceEndTime = $startTimeCarbon->copy()->addMinutes($totalDuration);
        
        // Buffer time is added AFTER for provider's cleanup (not shown to customer)
        $endTimeWithBuffer = $serviceEndTime->copy()->addMinutes($bufferTime);

        // Double-check slot is still available
        $isBooked = Appointment::where('provider_id', $provider->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->where(function ($query) use ($startTimeCarbon, $serviceEndTime) {
                $query->whereBetween('start_time', [$startTimeCarbon->format('H:i:s'), $serviceEndTime->format('H:i:s')])
                    ->orWhereBetween('end_time', [$startTimeCarbon->format('H:i:s'), $serviceEndTime->format('H:i:s')])
                    ->orWhere(function ($q) use ($startTimeCarbon, $serviceEndTime) {
                        $q->where('start_time', '<=', $startTimeCarbon->format('H:i:s'))
                          ->where('end_time', '>=', $serviceEndTime->format('H:i:s'));
                    });
            })
            ->exists();

        if ($isBooked) {
            throw new \Exception('This time slot is no longer available');
        }

        $appointment = Appointment::create([
            'customer_id' => $userId,
            'user_id' => $userId,
            'provider_id' => $provider->id,
            'service_id' => $services->first()->id,
            'appointment_date' => $date,
            'start_time' => $startTime,
            'end_time' => $serviceEndTime->format('H:i:s'), // Service end time only
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
     *
     * @param \App\Models\User $user
     * @param int $providerId
     * @param int $serviceId
     * @param string $date
     * @param string $startTime
     * @param string|null $notes
     * @return Appointment
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
