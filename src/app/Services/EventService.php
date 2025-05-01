<?php

namespace App\Services;
use App\Models\Event;
use App\Models\Ride;
use App\Repositories\Interfaces\EventRepositoryInterface;
use App\Services\Interfaces\EventServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventService extends BaseService implements EventServiceInterface
{
    protected $repository;

    public function __construct(EventRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getEventsForListing()
    {
        return $this->repository->getWithRelations(['user', 'photos']);
    }

    public function getEventWithRelations($eventId)
    {
        $event = $this->repository->find($eventId);
        $event->load(['user', 'rides.driver', 'rides.requests', 'photos', 'attendees.user']);
        return $event;
    }

    public function storeWithRelations(array $data, $userId)
    {
        $rideData = [];
        if (isset($data['has_ride_sharing']) && $data['has_ride_sharing']) {
            $rideData = [
                'vehicle_description' => $data['vehicle_description'] ?? null,
                'available_seats' => $data['available_seats'] ?? null,
                'meeting_location_name' => $data['meeting_location_name'] ?? null,
                'meeting_latitude' => $data['meeting_latitude'] ?? null,
                'meeting_longitude' => $data['meeting_longitude'] ?? null,
            ];
        }

        $photos = $data['photos'] ?? [];
        unset($data['photos']);

        unset($data['vehicle_description']);
        unset($data['available_seats']);
        unset($data['meeting_location_name']);
        unset($data['meeting_latitude']);
        unset($data['meeting_longitude']);

        $data['user_id'] = $userId;

        $event = $this->repository->create($data);

        if (!empty($photos)) {
            foreach ($photos as $photo) {
                $path = $photo->store('event_photos', 'public');
                $event->photos()->create([
                    'path' => $path,
                    'filename' => $photo->getClientOriginalName()
                ]);
            }
        }

        if (isset($data['has_ride_sharing']) && $data['has_ride_sharing'] && !empty($rideData['vehicle_description']) && !empty($rideData['available_seats'])) {
            Ride::create([
                'event_id' => $event->id,
                'driver_id' => $userId,
                'vehicle_description' => $rideData['vehicle_description'],
                'available_seats' => $rideData['available_seats'],
                'meeting_latitude' => $rideData['meeting_latitude'],
                'meeting_longitude' => $rideData['meeting_longitude'],
                'meeting_location_name' => $rideData['meeting_location_name']
            ]);
        }

        return $event;
    }

    public function updateWithRelations($eventId, array $data, $userId)
    {
        $event = $this->repository->find($eventId);

        if ($event->user_id !== $userId) {
            throw new \Exception("You do not have permission to update this event.");
        }

        if (!isset($data['has_ride_sharing'])) {
            $data['has_ride_sharing'] = false;
        }

        if (isset($data['photos']) && !empty($data['photos'])) {
            foreach ($data['photos'] as $photo) {
                $path = $photo->store('event_photos', 'public');
                $event->photos()->create([
                    'path' => $path,
                    'filename' => $photo->getClientOriginalName()
                ]);
            }
        }

        unset($data['photos']);

        $event = $this->repository->update($eventId, $data);

        return $event;
    }

    public function getEventsForFeed(Request $request)
    {
        $filters = $request->only([
            'search',
            'date_from',
            'date_to',
            'has_ride_sharing',
            'has_available_spots'
        ]);

        $events = $this->repository->getFilteredEvents($filters);
        $popularEvents = $this->repository->getPopularEvents();
        $upcomingEvents = $this->repository->getUpcomingEvents();

        return [
            'events' => $events,
            'popularEvents' => $popularEvents,
            'upcomingEvents' => $upcomingEvents
        ];
    }

    public function canUserManageEvent($eventId, $userId)
    {
        $event = $this->repository->find($eventId);
        return $event->user_id === $userId;
    }

    public function getUserEvents($eventId)
    {
        return $this->repository->getUserEvents($eventId);
    }

    public function getEventsForMap()
    {
        return $this->repository->all(['title', 'description', 'latitude', 'longitude', 'location_name']);
    }

    public function delete($id)
    {
        $event = $this->repository->find($id);

        foreach ($event->photos as $photo) {
            Storage::disk('public')->delete($photo->path);
        }

        return $this->repository->delete($id);
    }
}
