<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ride;
use Carbon\Carbon;
class EventController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        $events = Event::with(['user', 'photos'])->latest()->paginate(9);
        return view('events.event_list', compact('events'));
    }

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
            'vehicle_description' => 'required_if:has_ride_sharing,1|string|max:255|nullable',
            'available_seats' => 'required_if:has_ride_sharing,1|integer|min:1|nullable',
            'meeting_location_name' => 'required_if:has_ride_sharing,1|string|max:255|nullable',
            'meeting_latitude' => 'required_if:has_ride_sharing,1|numeric|nullable',
            'meeting_longitude' => 'required_if:has_ride_sharing,1|numeric|nullable',
        ]);

        $eventData = $validated;
        unset($eventData['vehicle_description']);
        unset($eventData['avalible_seats']);
        unset($eventData['meeting_location_name']);
        unset($eventData['meeting_latitude']);
        unset($eventData['meeting_longitude']);

        $eventData['user_id'] = Auth::id();
        $event = Event::create($eventData);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('event_photos', 'public');
                $event->photos()->create([
                    'path' => $path,
                    'filename' => $photo->getClientOriginalName()
                ]);
            }
        }

        if ($request->has('has_ride_sharing') && $request->has('vehicle_description') && $request->has('avalible_seats')) {
            Ride::create([
                'event_id' => $event->id,
                'driver_id' => Auth::id(),
                'vehicle_description' => $request->vehicle_description,
                'available_seats' => $request->available_seats,
                'meeting_latitude' => $request->meeting_latitude,
                'meeting_longitude' => $request->meeting_longitude,
                'meeting_location_name' => $request->meeting_location_name
            ]);
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        $event->load(['user', 'rides.driver', 'rides.requests', 'photos', 'attendees.user']);
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return redirect()->route('events.index')
                ->with('error', 'You do not have permission to edit this event.');
        }

        return view('events.edit', compact('event'));
    }

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
            'people_count' => 'required|integer|min:1',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if (!isset($validated['has_ride_sharing'])) {
            $validated['has_ride_sharing'] = false;
        }

        $event->update($validated);

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('event_photos', 'public');
                $event->photos()->create([
                    'path' => $path,
                    'filename' => $photo->getClientOriginalName()
                ]);
            }
        }

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully');
    }

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

    public function feed(Request $request)
    {
        // Podstawowe zapytanie
        $query = Event::with(['user', 'photos', 'attendees'])
            ->where('date', '>=', now());

        // Warunki filtrowania
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('location_name', 'like', "%$search%");
            });
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('date', '<=', $request->date_to);
        }

        if ($request->has('has_ride_sharing') && !empty($request->has_ride_sharing)) {
            $query->where('has_ride_sharing', true);
        }

        if ($request->has('has_available_spots') && !empty($request->has_available_spots)) {
            $query->whereRaw('people_count > (SELECT COALESCE(SUM(attendees_count), 0) FROM event_attendees WHERE event_attendees.event_id = events.id AND status = "accepted")');
        }

        // Pobierz wydarzenia z paginacją
        $events = $query->latest()->paginate(6);

        // Popularne i nadchodzące wydarzenia
        $popularEvents = Event::withCount(['acceptedAttendees as attendees_count'])
            ->where('date', '>=', now())
            ->orderBy('attendees_count', 'desc')
            ->take(5)
            ->get();

        $upcomingEvents = Event::where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();

        // Zwracanie widoku (poza blokiem warunkowym)
        return view('events.feed', compact('events', 'popularEvents', 'upcomingEvents'));
    }
    public function myEvents()
    {
        $events = auth()->user()->events()->paginate(6);
        return view('events.my_events', compact('events'));
    }
    public function Events_list()
    {
        $events = auth()->user()->events()->paginate(6);
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $upcomingEvents = Event::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->orderBy('date', 'asc')
            ->get();
        return view('events.events_list', compact('events') , compact('upcomingEvents'));
    }

}
