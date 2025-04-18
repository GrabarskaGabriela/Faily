<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class EventAttendee extends Model
{
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
}
