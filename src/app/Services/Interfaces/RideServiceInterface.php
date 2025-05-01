<?php

namespace App\Services\Interfaces;

use App\Models\Ride;
use Illuminate\Http\Request;

interface RideServiceInterface extends ServiceInterface
{
    public function getRideWithRelations($rideId);
    public function createRide(array $data, $userId);
    public function updateRide($rideId, array $data, $userId);
    public function deleteRide($rideId, $userId);
    public function canUserManageRide($rideId, $userId);
}
