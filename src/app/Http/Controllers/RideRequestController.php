<?php

namespace App\Http\Controllers;

use App\Models\RideRequest;
use Illuminate\Http\Request;

class RideRequestController extends Controller
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

    /**
     * Show the form for creating a new resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
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

        RideRequest::create($validated);

        return redirect()->route('events.show', $ride->event_id)
            ->with('success', 'Your application has been sent!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
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

        $rideRequest->update($validated);

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
