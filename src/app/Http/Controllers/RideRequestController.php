<?php

namespace App\Http\Controllers;

use App\Models\RideRequest;
use Illuminate\Http\Request;
use App\Notifications\RideRequestStatusChanged;

class RideRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $ride_id = $request->query('ride_id');

        if (!$ride_id) {
            return redirect()->route('events.index');
        }

        $ride = Ride::findOrFail($ride_id);

        if (Auth::id() !== $ride->driver_id) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', 'You do not have permission to view these submissions.');
        }

        $requests = RideRequest::where('ride_id', $ride_id)
            ->with('passenger')
            ->get();

        return view('ride_requests.index', compact('requests', 'ride'));
    }


    public function create(Request $request)
    {
        $ride_id = $request->query('ride_id');

        if (!$ride_id) {
            return redirect()->route('events.index');
        }

        $ride = Ride::with('driver', 'event')->findOrFail($ride_id);

        if (Auth::id() === $ride->driver_id) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', "You can't send an application to your own ride.");
        }

        $existingRequest = RideRequest::where('ride_id', $ride_id)
            ->where('passenger_id', Auth::id())
            ->first();

        if ($existingRequest) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', 'You have already sent an application for this passage.');
        }

        return view('ride_requests.create', compact('ride'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'message' => 'nullable|string',
        ]);

        $ride = Ride::findOrFail($request->ride_id);

        if (Auth::id() === $ride->driver_id) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', "You can't send an application to your own ride.");
        }

        $existingRequests = RideRequest::where('ride_id', $ride->id)
            ->where('status', 'accepted')
            ->count();

        if ($existingRequests >= $ride->available_seats) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', 'Brak dostępnych miejsc w tym przejeździe.');
        }

        $validated['passenger_id'] = Auth::id();
        $validated['status'] = 'pending';

        $rideRequest = RideRequest::create($validated);

        $ride->driver->notify(new RideRequestStatusChanged($rideRequest, $ride, Auth::user()));

        return redirect()->route('events.show', $ride->event_id)
            ->with('success', 'Your application has been sent!');
    }


    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RideRequest $rideRequest)
    {
        $ride = Ride::findOrFail($rideRequest->ride_id);

        if (Auth::id() !== $ride->driver_id) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', 'You do not have the authority to manage this request.');
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        if ($request->status === 'accepted') {
            $acceptedCount = RideRequest::where('ride_id', $ride->id)
                ->where('status', 'accepted')
                ->count();

            if ($acceptedCount >= $ride->available_seats) {
                return redirect()->route('ride_requests.index', ['ride_id' => $ride->id])
                    ->with('error', 'No seats available on this ride.');
            }
        }

        $oldStatus = $rideRequest->status;
        $rideRequest->update($validated);

        $rideRequest->passenger->notify(new RideRequestStatusChanged($rideRequest, $ride));

        $passengerName = $rideRequest->passenger->name ?? 'Passenger';
        $driverName = $ride->driver->name ?? 'Driver';
        $eventTitle = $ride->event->title ?? 'Event';

        activity('ride_requests')
            ->performedOn($rideRequest)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $request->status,
                'ride_id' => $ride->id,
                'event_id' => $ride->event_id,
                'event_title' => $eventTitle,
                'driver_id' => $ride->driver_id,
                'passenger_id' => $rideRequest->passenger_id
            ])
            ->log("The status of the travel request from {$passengerName} to {$driverName} for the event \"{$eventTitle}\" changed from \"{$oldStatus}\" to \"{$request->status}\"");

        return redirect()->route('ride_requests.index', ['ride_id' => $ride->id])
            ->with('success', 'Status has be updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RideRequest $rideRequest)
    {
        if (Auth::id() !== $rideRequest->passenger_id) {
            return redirect()->route('events.index')
                ->with('error', 'You do not have the authority to cancel this request.');
        }

        $event_id = Ride::findOrFail($rideRequest->ride_id)->event_id;
        $rideRequest->delete();

        return redirect()->route('events.show', $event_id)
            ->with('success', 'The application has been canceled!');
    }

}
