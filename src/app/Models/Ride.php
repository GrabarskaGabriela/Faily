<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Ride extends Model
{

    use LogsActivity;

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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'event_id',
                'driver_id',
                'vehicle_description',
                'available_seats',
                'meeting_location_name'
            ])
            ->logOnlyDirty()
            ->useLogName('rides')
            ->setDescriptionForEvent(function(string $eventName) {
                $driverName = $this->driver ? $this->driver->name : 'Driver';
                $eventTitle = $this->event ? $this->event->title : 'Event';

                return match($eventName) {
                    'created' => "{$driverName} offered a ride to the event \"{$eventTitle}\"",
                    'updated' => "Updated the ride {$driverName} for the event \"{$eventTitle}\"",
                    'deleted' => "Removed the ride {$driverName} for the event. \"{$eventTitle}\"",
                    default => $eventName
                };
            });
    }
}
