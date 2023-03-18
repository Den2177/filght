<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $guarded = false;

    public function passengers()
    {
        return $this->hasMany(Passenger::class, 'booking_id');
    }

    public function flightFrom()
    {
        return $this->belongsTo(Flight::class, 'flight_from');
    }

    public function flightBack()
    {
        return $this->belongsTo(Flight::class, 'flight_back');
    }
}
