<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\Interfaces\RideRepositoryInterface;
use App\Services\Interfaces\CacheServiceInterface;
use App\Services\Interfaces\RideServiceInterface;

class RideService extends BaseService implements RideServiceInterface
{
    protected $repository;
    protected $cacheService;

    public function __construct(
        RideRepositoryInterface $repository,
        ?CacheServiceInterface $cacheService
    )
    {
        parent::__construct($repository, $cacheService);

        $this->cacheTags = ['rides', 'events'];
        $this->cachePrefix = 'ride';
    }

    public function getRideWithRelations($rideId)
    {
        if (!$this->useCache()) {
            return $this->repository->getRideWithRelations($rideId);
        }

        return $this->cacheService->remember(
            "{$this->cachePrefix}.{$rideId}.with_relations",
            function () use ($rideId) {
                return $this->repository->getRideWithRelations($rideId);
            },
            $this->cacheTimes['find']
        );
    }

    public function createRide(array $data, $userId)
    {
        $data['driver_id'] = $userId;
        $ride = $this->repository->create($data);

        $event = Event::find($data['event_id']);
        if (!$event->has_ride_sharing) {
            $event->has_ride_sharing = true;
            $event->save();
        }

        if ($this->useCache()) {
            $this->cacheService->flushTags(['rides', 'events']);
            $this->cacheService->forget("event.{$data['event_id']}");
            $this->cacheService->forget("event.{$data['event_id']}.with_relations");
        }

        return $ride;
    }

    public function updateRide($rideId, array $data, $userId)
    {
        if (!$this->canUserManageRide($rideId, $userId)) {
            throw new \Exception('You do not have permission to update this ride.');
        }

        $result = $this->repository->update($rideId, $data);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$rideId}");
            $this->cacheService->forget("{$this->cachePrefix}.{$rideId}.with_relations");
            $this->cacheService->flushTags(['rides']);

            $eventId = $this->repository->getEventId($rideId);
            if ($eventId) {
                $this->cacheService->forget("event.{$eventId}");
                $this->cacheService->forget("event.{$eventId}.with_relations");
            }
        }

        return $result;
    }

    public function deleteRide($rideId, $userId)
    {
        if (!$this->canUserManageRide($rideId, $userId)) {
            throw new \Exception('You do not have permission to delete this ride.');
        }

        $eventId = $this->repository->getEventId($rideId);
        $result = $this->repository->delete($rideId);

        if ($this->useCache()) {
            $this->cacheService->forget("{$this->cachePrefix}.{$rideId}");
            $this->cacheService->forget("{$this->cachePrefix}.{$rideId}.with_relations");
            $this->cacheService->flushTags(['rides']);

            if ($eventId) {
                $this->cacheService->forget("event.{$eventId}");
                $this->cacheService->forget("event.{$eventId}.with_relations");
            }
        }

        return $result;
    }

    public function canUserManageRide($rideId, $userId)
    {
        return $this->repository->isUserDriver($rideId, $userId);
    }
}
