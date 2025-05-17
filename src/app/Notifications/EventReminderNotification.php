<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Ride;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EventReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;
    protected $ride;

    public function __construct(Event $event, Ride $ride = null)
    {
        $this->event = $event;
        $this->ride = $ride;
    }


    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Reminder of the event: ' . $this->event->title)
            ->greeting('Hello ' . $notifiable->name . ' sir!')
            ->line('A reminder that the event you signed up for will be held tomorrow:')
            ->line('**' . $this->event->title . '**')
            ->line('**Date:** ' . $this->event->date->format('d.m.Y H:i'))
            ->line('**Location:** ' . $this->event->location_name);

        if ($this->ride) {
            $mailMessage
                ->line('**Information about the ride:**')
                ->line('**Vehicle:** ' . $this->ride->vehicle_description)
                ->line('**Meeting Location:** ' . $this->ride->meeting_location_name)
                ->line('**Driver:** ' . $this->ride->driver->name);
        }

        return $mailMessage
            ->line("Can't take part? Remember to cancel your registration.")
            ->action('View event details', url('/events/' . $this->event->id))
            ->salutation('Team Faily,');
    }

    public function toArray($notifiable)
    {
        return [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'event_date' => $this->event->date,
            'ride_id' => $this->ride ? $this->ride->id : null,
        ];
    }
}
