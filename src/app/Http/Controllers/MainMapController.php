<?php

namespace App\Http\Controllers;

use App\Services\Interfaces\EventServiceInterface;

class MainMapController extends Controller
{
    protected $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
        $this->eventService = $eventService;
    }

    public function showMap()
    {
        $events = $this->eventService->getEventsForMap();
        return view('map', compact('events'));
    }
}
