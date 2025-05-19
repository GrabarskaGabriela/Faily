<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class Event extends Model
{
    use LogsActivity;

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
    protected $dates = ['date'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'title',
                'description',
                'date',
                'location_name',
                'people_count',
                'has_ride_sharing',
            ])
            ->logOnlyDirty()
            ->setDescriptionForEvent(function (string $eventName) {
                return match ($eventName) {
                    'created' => "Create new event: {$this->title}",
                    'updated' => "Updated event: {$this->title}",
                    'deleted' => "Deleted event: {$this->title}",
                    default => $eventName
                };
            });
    }
}
