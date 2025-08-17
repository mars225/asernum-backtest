<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hotel::all()->each(function ($hotel) {
            Room::factory()->count(rand(5, 15))->create([
                'hotel_id' => $hotel->id,
            ]);
        });
    }
}
