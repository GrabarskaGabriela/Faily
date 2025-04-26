<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\RideRequest;
use App\Models\Ride;

class RideRequestStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $rideRequest;
    protected $ride;
    protected $driver;

    /**
     * Create a new notification instance.
     */
    public function __construct(RideRequest $rideRequest, Ride $ride)
    {
        $this->rideRequest = $rideRequest;
        $this->ride = $ride;
        $this->driver = $ride->driver;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return $notifiable->email_notifications ? ['mail'] : [];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        if ($this->rideRequest->status === 'accepted') {
            return $this->buildAcceptedEmail($notifiable);
        } else {
            return $this->buildRejectedEmail($notifiable);
        }
    }

    protected function buildAcceptedEmail($notifiable)
    {
        return (new MailMessage)
            ->subject('Your request for a ride has been accepted!')
            ->greeting('Hello ' . $notifiable->name . ' sir!')
            ->line('Your request for a ride to the event "' . $this->ride->event->title . '" has been accepted by the driver ' . $this->driver->name . '.')
            ->line('Details of the ride:')
            ->line('• Date: ' . $this->ride->event->date->format('d.m.Y H:i'))
            ->line('• Meeting place: ' . $this->ride->meeting_location_name)
            ->line('• Vechicle: ' . $this->ride->vehicle_description)
            ->when($this->driver->phone && !empty(trim($this->driver->phone)), function ($message) {
                return $message->line('• Driver contact: ' . $this->driver->phone);
            })
            ->action('See event details', url('/events/' . $this->ride->event_id))
            ->line('We remind you to be punctual and inform the driver of any changes in plans.')
            ->line('Thank you for using our application!')
            ->line('Team Faily');
    }


    /**
     * Get the mail representation of the notification.
     */
    protected function buildRejectedEmail($notifiable)
    {
        return (new MailMessage)
            ->subject('Information about your transit request')
            ->greeting('Hello ' . $notifiable->name . 'sir!')
            ->line('Unfortunately, your request for a ride to the event "' . $this->ride->event->title . '" has been rejected.')
            ->line('We are so sorry 😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭😭')
            ->line('Have a great day!')
            ->line('Team Faily');
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
}
