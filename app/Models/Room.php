<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mattiverse\Userstamps\Traits\Userstamps;

class Room extends Model
{
    use Userstamps, HasFactory;


    protected $fillable = ['room_label', 'hotel_id', 'number', 'type', 'price_per_night', 'occupants', 'available'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
