<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $event_id = $request->get('event_id');

        if (!$event_id)
        {
            return redirect()->route('events.index')
                ->with('error', 'You must select the event for which you are creating a ride');
        }

        $event = Event::findOrFail($event_id);
        return redirect()->route('events.create', ['preset_event_id' => $event_id])
            ->with('message', 'Dodaj przejazd dla wydarzenia: ' . $event->title);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $event_id = $request->get('event_id');

        if (!$event_id){
            return redirect()->route('events.index')
                ->with('error', 'You must select the event for which you are creating a ride');
        }

        $event = Event::findOrFail($event_id);

        return view('ride.create', compact('event'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'vehicle_description' => 'required|string|max:255',
            'available_seats' => 'required|integer|min:1',
            'meeting_latitude' => 'required|numeric',
            'meeting_longitude' => 'required|numeric',
            'meeting_location_name' => 'required|string|max:255',
        ]);

        $validated['driver_id'] = Auth::id();
        $ride = Ride::create($validated);
        $event = Event::find($request->event_id);

        if(!$event->has_ride_sharing){
            $event->has_ride_sharing = true;
            $event->save();
        }

        return redirect()->route('events.show', $ride->event_id)
            ->with('success', 'Ride added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ride $ride)
    {
        $ride->load(['driver', 'event', 'requests.passenger']);
        return view('ride.show', compact('ride'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ride $ride)
    {
        if (Auth::id() !== $ride->driver_id) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', 'You do not have permission to edit this passage.');
        }

        return view('rides.edit', compact('ride'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ride $ride)
    {
        if (Auth::id() !== $ride->driver_id) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', 'You do not have permission to update this passage.');
        }

        $validated = $request->validate([
            'vehicle_description' => 'required|string|max:255',
            'available_seats' => 'required|integer|min:1',
            'meeting_latitude' => 'required|numeric',
            'meeting_longitude' => 'required|numeric',
            'meeting_location_name' => 'required|string|max:255',
        ]);

        $ride->update($validated);

        return redirect()->route('rides.show', $ride)
            ->with('success', 'The passage has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ride $ride)
    {
        if (Auth::id() !== $ride->driver_id) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', 'You do not have permission to delete this passage');
        }

        $event_id = $ride->event_id;
        $ride->delete();

        return redirect()->route('events.show', $event_id)
            ->with('success', 'The passage has been deleted!');
    }
}
