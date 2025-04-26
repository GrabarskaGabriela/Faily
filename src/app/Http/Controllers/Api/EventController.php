<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Resources\EventCollection;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    public function index(Request $request)
    {
        $query = Event::with(['user', 'photos', 'attendees']);

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

        if ($request->has('has_ride_sharing') && $request->has_ride_sharing) {
            $query->where('has_ride_sharing', true);
        }

        if ($request->has('has_available_spots') && $request->has_available_spots) {
            $query->whereRaw('people_count > (SELECT COALESCE(SUM(attendees_count), 0) FROM event_attendees WHERE event_attendees.event_id = events.id AND status = "accepted")');
        }

        if ($request->has('my_events.blade.php') && $request->my_events) {
            $query->where('user_id', Auth::id());
        }

        $sortBy = $request->sort_by ?? 'date';
        $sortOrder = $request->sort_order ?? 'asc';

        if (in_array($sortBy, ['date', 'title', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder === 'desc' ? 'desc' : 'asc');
        }

        $perPage = $request->per_page ?? 10;

        return new EventCollection($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required|date',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'location_name' => 'required|string|max:255',
            'has_ride_sharing' => 'boolean',
            'people_count' => 'required|integer|min:1',
        ]);

        $validated['user_id'] = Auth::id();

        $event = Event::create($validated);

        return new EventResource($event);
    }

    public function show(Event $event)
    {
        $event->load(['user', 'rides.driver', 'rides.requests', 'photos', 'attendees.user']);

        return new EventResource($event);
    }

    public function update(Request $request, Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return response()->json([
                'message' => 'Nie masz uprawnień do edycji tego wydarzenia.'
            ], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'date' => 'sometimes|required|date',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
            'location_name' => 'sometimes|required|string|max:255',
            'has_ride_sharing' => 'sometimes|boolean',
            'people_count' => 'sometimes|required|integer|min:1',
        ]);

        $event->update($validated);

        return new EventResource($event);
    }

    public function destroy(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return response()->json([
                'message' => 'Nie masz uprawnień do usunięcia tego wydarzenia.'
            ], 403);
        }

        $event->delete();

        return response()->json(['message' => 'Wydarzenie zostało usunięte.']);
    }

    public function popular()
    {
        $events = Event::withCount(['acceptedAttendees as attendees_count'])
            ->where('date', '>=', now())
            ->orderBy('attendees_count', 'desc')
            ->take(5)
            ->get();

        return new EventCollection($events);
    }

    public function upcoming()
    {
        $events = Event::where('date', '>=', now())
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();

        return new EventCollection($events);
    }
}
