<?php

namespace Database\Factories;

use App\Models\Hotel;
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
            'room_label'=> $this->faker->word(),
            'hotel_id'=> Hotel::inRandomOrder()->first()?->id ?? Hotel::factory(),
            'number' => $this->faker->unique()->numberBetween(100, 999),
            'type' => $this->faker->randomElement(['single', 'double', 'suite']),
            'price_per_night' => $this->faker->randomFloat(2, 50, 500),
            'occupants' => $this->faker->numberBetween(1, 4),
            'available' => true
        ];
    }
}
