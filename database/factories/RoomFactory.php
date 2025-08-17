<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'room_label'=> fake()->word(),
            'hotel_id'=> 1, // Assuming a default hotel ID for factory purposes
            'number' => fake()->unique()->numberBetween(100, 999),
            'type' => fake()->randomElement(['single', 'double', 'suite']),
            'price_per_night' => fake()->randomFloat(2, 50, 500),
            'occupants' => fake()->numberBetween(1, 4),
            'available' => true
        ];
    }
}
