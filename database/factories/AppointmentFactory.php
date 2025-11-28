<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-2 months', '+2 months');
        $startTime = fake()->time('H:i:s', '17:00:00');
        $duration = fake()->randomElement([30, 45, 60, 90, 120]);
        
        $start = Carbon::parse($startTime);
        $endTime = $start->copy()->addMinutes($duration)->format('H:i:s');
        
        $status = fake()->randomElement(['pending', 'confirmed', 'completed', 'cancelled']);
        $paymentStatus = $status === 'completed' ? 'paid' : 'pending';

        return [
            'appointment_date' => $date,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'status' => $status,
            'payment_status' => $paymentStatus,
            'notes' => fake()->optional()->sentence(),
        ];
    }
}
