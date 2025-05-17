<?php

namespace App\Repositories\Interfaces;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EventRepositoryInterface extends RepositoryInterface
{
    public function getWithRelations(array $relations, $perPage = 9);
    public function getFilteredEvents(array $filters = [], $perPage = 6);
    public function getPopularEvents($limit = 5);
    public function getUpcomingEvents($limit = 5);
    public function getUserEvents($userId, $perPage = 5 );
    public function isUserAttending($eventId, $userId);
    public function getAvailableSpotsCount($userId);
    public function hasAvailableSpots($eventId);
}
