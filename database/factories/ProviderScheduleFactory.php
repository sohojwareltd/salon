<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'provider_id' => Provider::factory(),
            'weekday' => fake()->numberBetween(0, 6),
            'start_time' => '09:00:00',
            'end_time' => '20:00:00',
            'is_off' => false,
        ];
    }

    public function off(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_off' => true,
            'start_time' => null,
            'end_time' => null,
        ]);
    }
}
