<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ride;
use App\Models\RideRequest;
use App\Models\User;


class NewRideRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected $rideRequest;
    protected $ride;
    protected $passenger;


    /**
     * Create a new notification instance.
     */
    public function __construct(RideRequest $rideRequest, Ride $ride, User $passenger)
    {
        $this->rideRequest = $rideRequest;
        $this->ride = $ride;
        $this->passenger = $passenger;
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
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New request for transit')
            ->greeting('Hello ' . $notifiable->name . ' sir!')
            ->line('You have received a new transit request from ' . $this->passenger->name . '.')
            ->line('Event: ' . $this->ride->event->title)
            ->line('Date: ' . $this->ride->event->date->format('d.m.Y H:i'))
            ->when($this->rideRequest->message, function ($message) {
                return $message->line('Message: ' . $this->rideRequest->message);
            })
            ->action('Manage travel requests', url('/ride-requests?ride_id=' . $this->ride->id))
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
