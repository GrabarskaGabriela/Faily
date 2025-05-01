<?php

namespace App\Repositories;

use App\Models\Ride;
use App\Repositories\Interfaces\RideRepositoryInterface;

class RideRepository extends BaseRepository implements RideRepositoryInterface
{

    public function model()
    {
        return Ride::class;
    }


    public function getRideWithRelations($rideId)
    {
        $ride = $this->find($rideId);
        $ride->load(['driver', 'event', 'requests.passenger']);
        return $ride;
    }


    public function isUserDriver($rideId, $userId)
    {
        $ride = $this->find($rideId);
        return $ride->driver_id === $userId;
    }


    public function getEventId($rideId)
    {
        $ride = $this->find($rideId);
        return $ride->event_id;
    }
}
