<?php

namespace App\Notifications;

use App\Models\Report;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBanned extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $lastReport;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user, ?Report $lastReport = null)
    {
        $this->user = $user;
        $this->lastReport = $lastReport;
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
        $mailMessage = (new MailMessage)
            ->subject('You has been banned')
            ->greeting('Hello ' . $notifiable->name . ' sir!')
            ->line('We regret to inform you that your account has been blocked due to violation of community rules.')
            ->line('Your account has received too many notifications from other users.');

        if ($this->lastReport && $this->lastReport->reason) {
            $mailMessage->line('The last notification included the following reason:')
                ->line('**' . $this->lastReport->reason . '**');
        }

        return $mailMessage
            ->line('If you have any questions, please don\'t hesitate to contact us.')
            ->line('We have no channel of contact yet.')
            ->salutation('Salam Alejkum 👳')
            ->salutation('Team Faily');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $this->user->id,
            'report_id' => $this->lastReport ? $this->lastReport->id : null,
            'banned_at' => now()->toDateTimeString(),
        ];
    }
}
