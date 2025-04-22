<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'latitude',
        'longitude',
        'location_name',
        'has_ride_sharing',
        'people_count'
    ];

    protected $casts = [
        'date' => 'datetime',
        'has_ride_sharing' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rides()
    {
        return $this->hasMany(Ride::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    public function attendees()
    {
        return $this->hasMany(EventAttendee::class);
    }

    public function acceptedAttendees()
    {
        return $this->hasMany(EventAttendee::class)->where('status', 'accepted');
    }

    public function hasAvailableSpots()
    {
        $totalAttendees = $this->acceptedAttendees()->sum('attendees_count');
        return $totalAttendees < $this->people_count;
    }

    public function getAvailableSpotsCount()
    {
        $totalAttendees = $this->acceptedAttendees()->sum('attendees_count');
        return max(0, $this->people_count - $totalAttendees);

    }

    public function isUserAttending($userid)
    {
        return $this->attendees()
            ->where('user_id', $userid)
            ->exists();
    }
}
