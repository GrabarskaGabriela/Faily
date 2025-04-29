<?php

namespace App\Repositories\Interfaces;

use App\Models\Ride;

interface RideRepositoryInterface extends RepositoryInterface
{
    public function getRideWithRelations($rideId);

    public function isUserDriver($rideId, $userId);

    public function getEventId($rideId);
}
