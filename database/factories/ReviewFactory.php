<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = fake()->numberBetween(1, 5);
        
        $comments = [
            'Excellent service! Very professional and friendly.',
            'Great experience, will definitely come back!',
            'Amazing results, highly recommend!',
            'Very skilled and attentive to detail.',
            'Good service, but could be better.',
            'Satisfied with the results.',
            'The provider was very knowledgeable and helpful.',
            'Love my new haircut! Exactly what I wanted.',
            'Professional and clean environment.',
            'Quick and efficient service.',
        ];
        
        return [
            'rating' => $rating,
            'comment' => $rating >= 4 ? fake()->randomElement($comments) : fake()->optional(0.8)->randomElement($comments),
        ];
    }
}
