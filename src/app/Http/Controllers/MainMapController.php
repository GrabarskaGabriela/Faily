<?php

namespace App\Http\Controllers;
use App\Models\Event;
class MainMapController extends Controller
{
    public function showMap()
    {
        $events = Event::all(['title', 'description', 'latitude', 'longitude', 'location_name']);
        return view('map', compact('events'));
    }

}
