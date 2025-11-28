<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $serviceAmount = fake()->randomFloat(2, 30, 200);
        $tipAmount = fake()->optional(0.7)->randomFloat(2, 5, 30) ?? 0;
        $totalAmount = $serviceAmount + $tipAmount;
        
        $status = fake()->randomElement(['completed', 'pending', 'failed']);
        
        return [
            'service_amount' => $serviceAmount,
            'tip_amount' => $tipAmount,
            'total_amount' => $totalAmount,
            'stripe_payment_intent_id' => $status === 'completed' ? 'pi_' . fake()->uuid() : null,
            'stripe_charge_id' => $status === 'completed' ? 'ch_' . fake()->uuid() : null,
            'status' => $status,
            'payment_method' => fake()->randomElement(['card', 'cash']),
            'paid_at' => $status === 'completed' ? fake()->dateTimeThisMonth() : null,
        ];
    }
}
