<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Event;

class SendEventRemindersCommand extends Command
{

    protected $signature = 'events:send-reminders';

    protected $description = 'Send reminder emails to event attendees for events happening tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        $this->info("Sending reminders for events on: $tomorrow");

        $events = Event::whereDate('date', $tomorrow)
            ->with(['attendees.user', 'rides.requests.passenger'])
            ->get();

        $eventCount = $events->count();
        $this->info("Found $eventCount events happening tomorrow");

        $notificationsSent = 0;

        foreach ($events as $event) {
            foreach ($event->attendees as $attendee) {
                if ($attendee->status === 'accepted' && $attendee->user) {
                    $rideRequest = RideRequest::where('passenger_id', $attendee->user_id)
                        ->whereHas('ride', function ($query) use ($event) {
                            $query->where('event_id', $event->id);
                        })
                        ->where('status', 'accepted')
                        ->with('ride')
                        ->first();

                    if ($rideRequest) {
                        $attendee->user->notify(new EventReminderNotification($event, $rideRequest->ride));
                    } else {
                        $attendee->user->notify(new EventReminderNotification($event));
                    }

                    $notificationsSent++;
                }
            }
        }

        $this->info("Successfully sent $notificationsSent reminder notifications");
        return 0;
    }
}
