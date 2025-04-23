<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class RideRequest extends Model
{
    use LogsActivity;
    protected $fillable = [
        'ride_id',
        'passenger_id',
        'status',
        'message'
    ];

    public function ride()
    {
        return $this->belongsTo(Ride::class);
    }

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['ride_id', 'passenger_id', 'status'])
            ->logOnlyDirty()
            ->useLogName('ride_requests')
            ->setDescriptionForEvent(function(string $eventName) {
                $passengerName = $this->passenger ? $this->passenger->name : 'Passenger';
                $driverName = $this->ride && $this->ride->driver ? $this->ride->driver->name : 'Driver';

                return match($eventName) {
                    'created' => "{$passengerName} asked for a seat in transit from {$driverName}",
                    'updated' => $this->wasChanged('status')
                        ? "Status of transit request {$passengerName} changed to \"{$this->status}\""
                        : "The request for passage has been updated {$passengerName}",
                    'deleted' => "{$passengerName} canceled a travel request",
                    default => $eventName
                };
            });
    }

    public function tapActivity(\Spatie\Activitylog\Contracts\Activity $activity, string $eventName)
    {
        if ($eventName === 'updated' && $this->wasChanged('status')) {
            $activity->properties = $activity->properties->merge([
                'old_status' => $this->getOriginal('status'),
                'new_status' => $this->status,
            ]);
        }
    }
}
