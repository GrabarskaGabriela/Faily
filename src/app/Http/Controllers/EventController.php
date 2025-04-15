<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::with('user')->latest()->paginate(10);
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date',
            'latitude' =>  'required|numeric',
            'longitude' =>  'required|numeric',
            'location_name' => 'required|string|max:255',
            'has_ride_sharing' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();

        $event = new Event($validated);
        $event->save(); // Dodaj tę linię, aby zapisać wydarzenie w bazie danych

        if ($request->has_ride_sharing) {
            return redirect()->route('rides.create', ['event_id' => $event->id])
                ->with('success', 'Event created successfully. Now add transit information.');
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['user', 'rides.driver', 'rides.requests']);
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return redirect()->route('events.index')
                ->with('error', 'You do not have permission to edit this event.');
        }

        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return redirect()->route('events.index')
                ->with('error', 'You do not have permission to update this event.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'latitude' =>  'required|numeric',
            'longitude' =>  'required|numeric',
            'location_name' => 'required|string|max:255',
            'has_ride_sharing' => 'boolean',
        ]);

        $event->update($validated);
        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return redirect()->route('events.index')
                ->with('error', 'You do not have permission to delete this event.');
        }

        $event->delete();
        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully');
    }
}
