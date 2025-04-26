<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAttendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EventAttendeeStatusChanged;

class EventAttendeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Event $event)
    {
        if (Auth::id() !== $event->user_id) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You do not have permission to view the list of participants.');
        }
        $attendees = $event->attendees()->with('user')->get();

        return view('events.attendees.partials.attendees-table', compact('event', 'attendees'));
    }


    public function create(Event $event)
    {
        if (Auth::id() === $event->user_id) {
            return redirect()->route('events.show', $event)
                ->with('info', 'You are the organizer of this event.');
        }

        if ($event->isUserAttending(Auth::id())) {
            return redirect()->route('events.show', $event)
                ->with('info', 'You are already signed up for this event.');
        }

        if (!$event->hasAvailableSpots()) {
            return redirect()->route('events.show', $event)
                ->with('error', 'No seats available for this event.');
        }

        return view('events.attendees.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $validated = $request->validate([
            'message' => 'nullable|string|max:500',
            'attendees_count' => 'required|integer|min:1|max:10',
        ]);

        if (Auth::id() === $event->user_id) {
            return redirect()->route('events.show', $event)
                ->with('info', 'You are the organizer of this event.');
        }

        if ($event->isUserAttending(Auth::id())) {
            return redirect()->route('events.show', $event)
                ->with('info', 'You are already signed up for this event.');
        }

        $avaliableSpots = $event->getAvailableSpotsCount();
        if($avaliableSpots <$validated['attendees_count'])
        {
            return redirect()->route('events.show', $event)
                ->with('error', 'Not enough seats available. AvailableSpots: {$availableSpots}.');
        }

        $eventAttendee = new EventAttendee([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'status' => 'pending',
            'message' => $validated['message'],
            'attendees_count' => $validated['attendees_count'],
        ]);

        $eventAttendee->save();

        return redirect()->route('events.show', $event)
            ->with('success', 'Your application has been sent!');
    }

    public function update(Request $request, Event $event, EventAttendee $attendee)
    {
        if (Auth::id() !== $event->user_id) {
            return redirect()->route('events.show', $event)
                ->with('error', 'You dont have permission to manage notifications.');
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        if ($request->status === 'accepted') {
            $availableSpots = $event->getAvailableSpotsCount();
            if ($availableSpots < $attendee->attendees_count) {
                return redirect()->route('events.attendees.index', $event)
                    ->with('error', "Not enough seats available. Available seats: {$availableSpots}.");
            }
        }

        $oldStatus = $attendee->status;
        $attendee->status = $request->status;
        $attendee->save();

        $attendee->user->notify(new EventAttendeeStatusChanged($attendee, $event, $oldStatus));

        $userName = $attendee->user->name ?? 'User';

        activity('event_attendees')
            ->performedOn($attendee)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $attendee->status,
                'event_id' => $event->id,
                'event_title' => $event->title
            ])
            ->log("Changed the status of {$userName} participation in the \"{$event->title}\"  event from \"{$oldStatus}\" to \"{$attendee->status}\"");


        return redirect()->route('events.attendees.index', $event)
            ->with('success', 'Participant status has been updated!');
    }

    public function destroy(Event $event, EventAttendee $attendee)
    {
        if (Auth::id() !== $attendee->user_id && Auth::id() !== $event->user_id) {
            return redirect()->route('events.show', $event)
                ->with('error', 'Nie masz uprawnień do anulowania tego zgłoszenia.');
        }

        $attendee->delete();

        if (Auth::id() === $event->user_id)
        {
            return redirect()->route('events.attendess.index', $event)
                ->with('success', 'Participant has been deleted!');
        }
        else
        {
            return redirect()->route('events.show', $event)
                ->with('success', 'Participant has been deleted.');
        }
    }
}

