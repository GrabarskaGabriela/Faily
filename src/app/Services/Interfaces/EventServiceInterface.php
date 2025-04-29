<?php

namespace App\Services\Interfaces;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface EventServiceInterface extends ServiceInterface
{
    public function getEventsForListing();

    public function getEventWithRelations($eventId);

    public function storeWithRelations(array $data, $userId);

    public function updateWithRelations($eventId, array $data, $userId);

    public function getEventsForFeed(Request $request);

    public function canUserManageEvent($eventId, $userId);
    public function getUserEvents($eventId);

    public function getEventsForMap();
}
