<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Interfaces\RideRepositoryInterface;
use App\Services\Interfaces\RideServiceInterface;

class RideService extends BaseService implements RideServiceInterface
{
    protected $repository;

    public function __construct(RideRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getRideWithRelations($rideId)
    {
        return $this->repository->getRideWithRelations($rideId);
    }

    public function createRide(array $data, $userId)
    {
        $data['driver_id'] = $userId;
        $ride = $this->repository->create($data);

        // Update the event has_ride_sharing flag if needed
        $event = Event::find($data['event_id']);
        if (!$event->has_ride_sharing) {
            $event->has_ride_sharing = true;
            $event->save();
        }

        return $ride;
    }

    public function updateRide($rideId, array $data, $userId)
    {
        if (!$this->canUserManageRide($rideId, $userId)) {
            throw new \Exception('You do not have permission to update this ride.');
        }

        return $this->repository->update($rideId, $data);
    }

    public function deleteRide($rideId, $userId)
    {
        if (!$this->canUserManageRide($rideId, $userId)) {
            throw new \Exception('You do not have permission to delete this ride.');
        }

        $eventId = $this->repository->getEventId($rideId);
        return $this->repository->delete($rideId);
    }

    public function canUserManageRide($rideId, $userId)
    {
        return $this->repository->isUserDriver($rideId, $userId);
    }
}
