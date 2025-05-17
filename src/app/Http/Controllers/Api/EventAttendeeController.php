<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EventAttendeeResource;
use App\Http\Resources\EventAttendeeCollection;
use App\Models\Event;
use App\Models\EventAttendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventAttendeeController extends Controller
{
    public function index(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return response()->json([
                'message' => 'You do not have permission to view the list of participants.'
            ], 403);
        }

        $attendees = $event->attendees()->with('user')->get();

        return new EventAttendeeCollection($attendees);
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'attendees_count' => 'required|integer|min:1|max:10',
        ]);

        if (Auth::id() === $event->user_id) {
            return response()->json([
                'message' => 'You are the organizer of this event.'
            ], 422);
        }

        if ($event->isUserAttending(Auth::id())) {
            return response()->json([
                'message' => 'You are already signed up for this event.'
            ], 422);
        }

        $availableSpots = $event->getAvailableSpotsCount();
        if ($availableSpots < $validated['attendees_count']) {
            return response()->json([
                'message' => "Not enough seats available. Available seats: {$availableSpots}."
            ], 422);
        }

        $eventAttendee = new EventAttendee([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'message' => $validated['message'],
            'attendees_count' => $validated['attendees_count'],
        ]);

        $eventAttendee->save();

        return new EventAttendeeResource($eventAttendee);
    }

    public function update(Request $request, Event $event, EventAttendee $attendee)
    {
        if (Auth::id() !== $event->user_id) {
            return response()->json([
                'message' => 'You don\'t have permission to manage notifications.'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        if ($request->status === 'accepted') {
            $availableSpots = $event->getAvailableSpotsCount();
            if ($availableSpots < $attendee->attendees_count) {
                return response()->json([
                    'message' => "Not enough seats available. Available seats: {$availableSpots}."
                ], 422);
            }
        }

        $attendee->status = $request->status;
        $attendee->save();

        return new EventAttendeeResource($attendee);
    }

    public function destroy(Event $event, EventAttendee $attendee)
    {
        if (Auth::id() !== $attendee->user_id && Auth::id() !== $event->user_id) {
            return response()->json([
                'message' => 'You do not have the authority to cancel this request.'
            ], 403);
        }

        $attendee->delete();

        return response()->json(['message' => 'The application has been cancelled.']);
    }

    public function myAttendees()
    {
        $attendees = EventAttendee::with(['event', 'user'])
            ->whereHas('event', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        return new EventAttendeeCollection($attendees);
    }

    public function myEvents()
    {
        $attendees = EventAttendee::with(['event', 'user'])
            ->where('user_id', Auth::id())
            ->get();

        return new EventAttendeeCollection($attendees);
    }
}
