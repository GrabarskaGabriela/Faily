<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\EventServiceInterface;
use Carbon\Carbon;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventServiceInterface $eventService)
    {
<<<<<<< HEAD
        $this->middleware('auth')->except(['index', 'show', 'feed']);
=======
        $this->middleware('auth')->except(['index', 'show']);
>>>>>>> origin/wodzu
        $this->eventService = $eventService;
    }

    public function index(Request $request)
    {
        $events = $this->eventService->getEventsForListing();
        return view('events.my_events', compact('events'));
<<<<<<< HEAD
    }

    /**
     * Display a feed of events with filters
     */
    public function feed(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $upcomingEvents = Event::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();

        $query = Event::query()->with(['user', 'photos', 'attendees', 'acceptedAttendees'])
            ->where('date', '>=', now())
            ->orderBy('date', 'asc');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location_name', 'like', "%{$search}%");
            });
        }


        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        // Apply ride sharing filter
        if ($request->has('has_ride_sharing') && $request->has_ride_sharing == 1) {
            $query->where('has_ride_sharing', true);
        }

        if ($request->has('has_available_spots') && $request->has_available_spots == 1) {
            $query->whereRaw('people_count > (
                SELECT COALESCE(SUM(attendees_count), 0)
                FROM attendees
                WHERE event_id = events.id AND status = "accepted"
            )');
        }

        $events = $query->paginate(6);

        $events->appends($request->all());

        return view('events.feed', compact('events', 'upcomingEvents'));
=======
>>>>>>> origin/wodzu
    }

    public function create(Request $request)
    {
        $preset_event_id = $request->input('preset_event_id');
        $preset_event = null;

        if ($preset_event_id) {
            $preset_event = $this->eventService->findById($preset_event_id);
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

        $event = $this->eventService->storeWithRelations($validated, Auth::id());

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
<<<<<<< HEAD
        $event = $this->eventService->getEventWithRelations($event->id);
=======
        $event =  $this->eventService->getEventWithRelations($event->id);
>>>>>>> origin/wodzu
        return view('events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        if (!$this->eventService->canUserManageEvent($event->id, Auth::id())) {
            return redirect()->route('events.index')
                ->with('error', 'You do not have permission to edit this event.');
        }

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        if (!$this->eventService->canUserManageEvent($event->id, Auth::id())) {
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

        $event = $this->eventService->updateWithRelations($event->id, $validated, Auth::id());

        return redirect()->route('events.show', $event)
            ->with('success', 'Event updated successfully');
    }

    public function destroy(Event $event)
    {
        if (!$this->eventService->canUserManageEvent($event->id, Auth::id())) {
            return redirect()->route('events.index')
                ->with('error', 'You do not have permission to delete this event.');
        }

        $this->eventService->delete($event->id);

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully');
    }

<<<<<<< HEAD
    public function Events_list()
    {
        $events = auth()->user()->events()->paginate(6);
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $upcomingEvents = Event::whereYear('date', $currentYear)
            ->whereMonth('date', $currentMonth)
            ->orderBy('date', 'asc')
            ->get();
        return view('events.event_list', compact('events', 'upcomingEvents'));
=======
    public function feed(Request $request)
    {
        $eventData = $this->eventService->getEventsForFeed($request);

        return view('events.feed', [
            'events' => $eventData['events'],
            'popularEvents' => $eventData['popularEvents'],
            'upcomingEvents' => $eventData['upcomingEvents']
        ]);
    }
    public function myEvents()
    {
        $events = $this->eventService->getUserEvents(Auth::id());
        return view('events.my_events', compact('events'));
>>>>>>> origin/wodzu
    }

    public function myEvents()
    {
        $events = $this->eventService->getUserEvents(Auth::id());
        return view('events.my_events', compact('events'));
    }
}
