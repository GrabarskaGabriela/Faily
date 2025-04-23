<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class EventAttendee extends Model
{
    use LogsActivity;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'message',
        'attendees_count'
    ];

    protected $casts = [
        'attendees_count' => 'integer',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->useLogName('event_attendee')
            ->setDescriptionForEvent(function (string $eventName) {
                $userName = $this->user ? $this->user->name : 'User';
                $eventTitle = $this->event ? $this->event->title : 'Event';

                return match ($eventName) {
                    'created' => "{$userName} has applied to attend the event \"{$eventTitle}\"",
                    'updated' => $this->wasChanged('status')
                        ? "Participation status of {$userName} in event  \"{$eventTitle}\" changed to \"{$this->status}\""
                        : "Updated participation data {$userName} in event  \"{$eventTitle}\"",
                    'deleted' => "{$userName} cancelled participation in event  \"{$eventTitle}\"",
                    default => $eventName
                };
            });
    }

    public function tapActivity(\Spatie\Activitylog\Contracts\Activity $activity, string $eventName)
    {
        if($eventName == 'updated' && $this->wasChanged('status')){
            $activity->properties = $activity->properties->merge([
                'old_status' => $this->getOriginal('status'),
                'new_status' => $this->status,
            ]);
        }
    }

}
