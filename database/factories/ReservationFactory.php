<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => fake()->numberBetween(1, 100),
            'room_id' => fake()->numberBetween(1, 50),
            'start_date'=> fake()->dateTimeBetween('now', '+1 month'),
            'end_date'=> fake()->dateTimeBetween('+1 month', '+2 months'),
            'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled', 'finished']),
        ];
    }
}
