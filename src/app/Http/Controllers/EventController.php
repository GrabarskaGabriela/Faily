<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RideController;
use App\Models\Ride;

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
        $events = Event::with(['user', 'photos'])->latest()->paginate(9);
        return view('events.event_list', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $preset_event_id = $request->input('preset_event_id');
        $preset_event = null;

        if ($preset_event_id)
        {
            $preset_event = Event::findOrFail($preset_event_id);
        }

        return view('events.create', compact('preset_event'));
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
            'people_count' => 'required|integer|min:1',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            // Nowe pola do przejazdu
            'vehicle_description' => 'required_if:has_ride_sharing,1|string|max:255|nullable',
            'available_seats' => 'required_if:has_ride_sharing,1|integer|min:1|nullable',
            'meeting_location_name' => 'required_if:has_ride_sharing,1|string|max:255|nullable',
            'meeting_latitude' => 'required_if:has_ride_sharing,1|numeric|nullable',
            'meeting_longitude' => 'required_if:has_ride_sharing,1|numeric|nullable',
        ]);

        // Usuwamy dane przejazdu z danych wydarzenia
        $eventData = $validated;
        unset($eventData['vehicle_description']);
        unset($eventData['avalible_seats']);
        unset($eventData['meeting_location_name']);
        unset($eventData['meeting_latitude']);
        unset($eventData['meeting_longitude']);

        $eventData['user_id'] = Auth::id();
        $event = Event::create($eventData);

        // Obsługa przesyłania zdjęć
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('event_photos', 'public');
                $event->photos()->create([
                    'path' => $path,
                    'filename' => $photo->getClientOriginalName()
                ]);
            }
        }

        // Jeśli ma być współdzielenie przejazdów i wszystkie wymagane dane są podane
        if ($request->has('has_ride_sharing') && $request->has('vehicle_description') && $request->has('avalible_seats')) {
            // Tworzenie przejazdu
            Ride::create([
                'event_id' => $event->id,
                'driver_id' => Auth::id(),
                'vehicle_description' => $request->vehicle_description,
                'avalible_seats' => $request->avalible_seats,
                'meeting_latitude' => $request->meeting_latitude,
                'meeting_longitude' => $request->meeting_longitude,
                'meeting_location_name' => $request->meeting_location_name
            ]);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        $event->load(['user', 'rides.driver', 'rides.requests', 'photos']);
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
