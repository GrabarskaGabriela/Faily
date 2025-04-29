<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface EventAttendeeRepositoryInterface extends RepositoryInterface
{
    public function getEventAttendees($eventId);

    public function isUserAttending($eventId, $userId);
    public function createAttendeeRequest(array $data);
    public function updateStatus($attendeeId, $status);
    public function getEventOwner($eventId);
}
