<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $expertises = [
            'Hair Cutting, Hair Styling',
            'Hair Coloring, Highlights',
            'Hair Extensions, Keratin Treatment',
            'Nail Art, Manicure, Pedicure',
            'Massage Therapy, Spa Treatments',
            'Makeup Artist, Bridal Makeup',
            'Beard Trim, Hot Towel Shave',
            'Balayage, Ombre Coloring'
        ];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'photo' => null,
            'expertise' => fake()->randomElement($expertises),
            'bio' => fake()->paragraph(2),
            'average_rating' => fake()->randomFloat(2, 3.5, 5.0),
            'total_reviews' => fake()->numberBetween(0, 150),
            'is_active' => true,
            'break_start' => fake()->randomElement(['12:00:00', '13:00:00', null]),
            'break_end' => fake()->randomElement(['13:00:00', '14:00:00', null]),
        ];
    }
}
