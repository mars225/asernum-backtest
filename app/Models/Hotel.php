<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Mattiverse\Userstamps\Traits\Userstamps;

class Hotel extends Model
{
    use Userstamps, HasFactory;

    protected $fillable = ['label','code','address','city','country','stars'];
}
