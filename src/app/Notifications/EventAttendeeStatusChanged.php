<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\EventAttendee;
use App\Models\Event;

class EventAttendeeStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $eventAttendee;
    protected $event;
    protected $oldStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventAttendee $eventAttendee, Event $event, $oldStatus)
    {
        $this->eventAttendee = $eventAttendee;
        $this->event = $event;
        $this->oldStatus = $oldStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        if ($this->rideRequest->status === 'accepted') {
            return $this->buildAcceptedEmail($notifiable);
        } else {
            return $this->buildRejectedEmail($notifiable);
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }

    protected function buildAcceptedEmail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your request for a ride has been approved!')
            ->greeting('Hello ' . $notifiable->name . ' sir!')
            ->line('Your request for a ride to the event  "' . $this->ride->event->title . '" has been accepted by the driver ' . $this->driver->name . '.')
            ->line('Details of the ride:')
            ->line('• Date of event: ' . $this->ride->event->date->format('d.m.Y H:i'))
            ->line('• Meeting place: ' . $this->ride->meeting_location_name)
            ->line('• Vehicle: ' . $this->ride->vehicle_description)
            ->when($this->driver->phone && !empty(trim($this->driver->phone)), function ($message) {
                return $message->line('• Driver contact: ' . $this->driver->phone);
            })
            ->action('See event details', url('/events/' . $this->ride->event_id))
            ->line('We remind you to be punctual and inform the driver of any changes in plans.')
            ->salutation('Thank you for using our application!')
            ->salutation('Team Faily');
    }

    protected function buildRejectedEmail($notifiable)
    {
        return (new MailMessage)
            ->subject('Information about your transit request')
            ->greeting('Hello ' . $notifiable->name . 'sir!')
            ->line('Unfortunately, your request for a ride to the event "' . $this->ride->event->title . '" has been rejected by the driver.')
            ->line('We are so sorry 😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭')
            ->salutation('Have a great day!')
            ->salutation('Team Faily');

    }
}
