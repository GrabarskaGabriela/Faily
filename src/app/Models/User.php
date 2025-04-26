<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'first_name',
        'last_name',
        'age',
        'phone',
        'description',
        'photo_path',
        'photo_updated_at',
        'password_updated_at',
        'last_login_at',
        'two_factor_enabled',
        'email_notifications',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'photo_updated_at' => 'datetime',
        'password_updated_at' => 'datetime',
        'last_login_at' => 'datetime',
        'two_factor_enabled' => 'boolean',
        'email_notifications' => 'boolean',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function rides()
    {
        return $this->hasMany(Ride::class, 'driver_id');
    }

    public function rideRequests()
    {
        return $this->hasMany(RideRequest::class, 'passenger_id');
    }

    public function getAvatarAttribute()
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }

        return asset('images/default-avatar.png');
    }


    public function eventAttendees()
    {
        return $this->hasMany(EventAttendee::class);
    }

    public function acceptedEventAttendees()
    {
        return $this->hasMany(EventAttendee::class)->where('status', 'accepted');
    }

    public function pendingEventAttendees()
    {
        return $this->hasMany(EventAttendee::class)->where('status', 'pending');
    }
    public function myEvents()
    {
        $events = auth()->user()->events()->paginate(6);
        return view('events.my_events', compact('events'));
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
                'email',
                'first_name',
                'last_name',
                'phone'
            ])
            ->logOnlyDirty()
            ->useLogName('users')
            ->dontLogIfAttributesChangedOnly(['password', 'remember_token', 'last_login_at'])
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => "New user registered: {$this->name}",
                    'updated' => "User profile updated: {$this->name}",
                    'deleted' => "User profile deleted: {$this->name}",
                    default => $eventName
                };
            });
    }

}
