<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventAttendee;
use App\Notifications\EventAttendeeStatusChanged;
use App\Repositories\Interfaces\EventAttendeeRepositoryInterface;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Services\Interfaces\EventAttendeeServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Spatie\Activitylog\Facades\Activity;

class EventAttendeeService extends BaseService implements EventAttendeeServiceInterface
{

    protected $repository;

    protected $eventRepository;

    public function __construct(
        EventAttendeeRepositoryInterface $repository,
        EventRepositoryInterface $eventRepository
    ) {
        $this->repository = $repository;
        $this->eventRepository = $eventRepository;
    }

    public function getEventAttendees(Event $event)
    {
        return $this->repository->getEventAttendees($event->id);
    }

    public function canUserRegisterForEvent($eventId, $userId)
    {
        $event = $this->eventRepository->find($eventId);

        $result = [
            'canRegister' => true,
            'message' => null
        ];

        if ($event->user_id === $userId) {
            $result['canRegister'] = false;
            $result['message'] = 'Jesteś organizatorem tego wydarzenia.';
            return $result;
        }

        if ($this->repository->isUserAttending($eventId, $userId)) {
            $result['canRegister'] = false;
            $result['message'] = 'Jesteś już zapisany na to wydarzenie.';
            return $result;
        }

        if (!$this->eventRepository->hasAvailableSpots($eventId)) {
            $result['canRegister'] = false;
            $result['message'] = 'Brak wolnych miejsc na to wydarzenie.';
            return $result;
        }

        return $result;
    }

    public function registerForEvent(Event $event, array $data, $userId)
    {
        $availableSpots = $this->eventRepository->getAvailableSpotsCount($event->id);
        if ($availableSpots < $data['attendees_count']) {
            throw new \Exception("Nie wystarczająca liczba miejsc. Dostępne miejsca: {$availableSpots}.");
        }

        $attendeeData = [
            'event_id' => $event->id,
            'user_id' => $userId,
            'status' => 'pending',
            'message' => $data['message'] ?? null,
            'attendees_count' => $data['attendees_count'],
        ];

        return $this->repository->createAttendeeRequest($attendeeData);
    }

    public function updateAttendeeStatus(Event $event, EventAttendee $attendee, $status, $userId)
    {
        if ($event->user_id !== $userId) {
            throw new \Exception('You do not have permission to manage participants.');
        }

        if ($status === 'accepted') {
            $availableSpots = $this->eventRepository->getAvailableSpotsCount($event->id);
            if ($availableSpots < $attendee->attendees_count) {
                throw new \Exception("Not enough seats available. Available seats: {$availableSpots}.");
            }
        }

        $oldStatus = $attendee->status;
        $attendee = $this->repository->updateStatus($attendee->id, $status);

        $attendee->user->notify(new EventAttendeeStatusChanged($attendee, $event, $oldStatus));

        $userName = $attendee->user->name ?? 'User';

        $statusMessage = '';
        if ($status === 'accepted') {
            $statusMessage = "Status użytkownika {$attendee->user->name} został zmieniony na 'zaakceptowany'. Użytkownik jest teraz zapisany na wydarzenie.";
        } else if ($status === 'rejected') {
            $statusMessage = "Status użytkownika {$attendee->user->name} został zmieniony na 'odrzucony'.";
        }

        activity('event_attendees')
            ->performedOn($attendee)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $attendee->status,
                'event_id' => $event->id,
                'event_title' => $event->title
            ])
            ->log("Changed the status of {$userName} participation in the \"{$event->title}\" event from \"{$oldStatus}\" to \"{$attendee->status}\"");

        return $attendee;
    }

    public function cancelAttendance(Event $event, EventAttendee $attendee, $userId)
    {
        if ($userId !== $attendee->user_id && $userId !== $event->user_id) {
            throw new \Exception('You do not have permissions to cancel this application.');
        }

        return $this->repository->delete($attendee->id);
    }
}
