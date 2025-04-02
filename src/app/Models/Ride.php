<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    protected $fillable = [
        'event_id',
        'driver_id',
        'vehicle_description',
        'available_seats',
        'meeting_latitude',
        'meeting_longitude',
        'meeting_location_name'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function requests()
    {
        return $this->hasMany(RideRequest::class);
    }
}
