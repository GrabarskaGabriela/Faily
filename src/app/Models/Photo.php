<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Photo extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'event_id',
        'path',
        'filename',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['event_id', 'filename'])
            ->logOnlyDirty()
            ->useLogName('photos')
            ->setDescriptionForEvent(function (string $eventName) {
                $eventTitle = $this->event ? $this->event->title : 'Event';

                return match ($eventName) {
                    'created' => "Add new photo to event \"{$eventTitle}\"",
                    'deleted' => "Deleted photo form event \"{$eventTitle}\"",
                    default => $eventName
                };
            });
    }

}
