<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hotel>
 */
class HotelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(),
            'label' => fake()->company(),
            'code'=> fake()->unique()->bothify('HOTEL-###'),
            'address'=> fake()->streetAddress(),
            'city'=> fake()->city(),
            'country'=> fake()->country(),
            'stars'=> fake()->numberBetween(1, 5)
        ];
    }
}
