<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $services = [
            ['name' => 'Haircut', 'category' => 'Hair', 'duration' => 30, 'price' => 35.00],
            ['name' => 'Hair Coloring', 'category' => 'Hair', 'duration' => 90, 'price' => 85.00],
            ['name' => 'Balayage', 'category' => 'Hair', 'duration' => 120, 'price' => 150.00],
            ['name' => 'Hair Styling', 'category' => 'Hair', 'duration' => 45, 'price' => 45.00],
            ['name' => 'Keratin Treatment', 'category' => 'Hair', 'duration' => 150, 'price' => 200.00],
            ['name' => 'Manicure', 'category' => 'Nails', 'duration' => 45, 'price' => 30.00],
            ['name' => 'Pedicure', 'category' => 'Nails', 'duration' => 60, 'price' => 45.00],
            ['name' => 'Gel Nails', 'category' => 'Nails', 'duration' => 75, 'price' => 55.00],
            ['name' => 'Facial', 'category' => 'Spa', 'duration' => 60, 'price' => 75.00],
            ['name' => 'Swedish Massage', 'category' => 'Spa', 'duration' => 60, 'price' => 80.00],
            ['name' => 'Deep Tissue Massage', 'category' => 'Spa', 'duration' => 90, 'price' => 110.00],
            ['name' => 'Makeup Application', 'category' => 'Makeup', 'duration' => 60, 'price' => 65.00],
            ['name' => 'Bridal Makeup', 'category' => 'Makeup', 'duration' => 120, 'price' => 150.00],
            ['name' => 'Beard Trim', 'category' => 'Barber', 'duration' => 20, 'price' => 20.00],
            ['name' => 'Hot Towel Shave', 'category' => 'Barber', 'duration' => 30, 'price' => 35.00],
        ];

        $service = fake()->randomElement($services);

        return [
            'name' => $service['name'],
            'description' => fake()->sentence(),
            'duration' => $service['duration'],
            'price' => $service['price'],
            'category' => $service['category'],
            'image' => null,
            'is_active' => true,
        ];
    }
}
