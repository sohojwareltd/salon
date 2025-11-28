<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProviderWalletEntryFactory extends Factory
{
    public function definition(): array
    {
        $serviceAmount = fake()->randomFloat(2, 20, 200);
        $salonCommission = 0.30; // 30%
        $providerCommission = 0.70; // 70%
        
        $salonAmount = $serviceAmount * $salonCommission;
        $providerAmount = $serviceAmount * $providerCommission;
        $tipAmount = fake()->randomFloat(2, 0, 50);
        $totalProviderAmount = $providerAmount + $tipAmount;

        return [
            'provider_id' => Provider::factory(),
            'appointment_id' => Appointment::factory(),
            'payment_id' => Payment::factory(),
            'service_amount' => $serviceAmount,
            'salon_amount' => $salonAmount,
            'provider_amount' => $providerAmount,
            'tips_amount' => $tipAmount,
            'total_provider_amount' => $totalProviderAmount,
            'type' => 'earning',
            'notes' => 'Payment received for service',
        ];
    }
}
