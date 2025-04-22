<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RideResource;
use App\Http\Resources\RideCollection;
use App\Models\Ride;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideController extends Controller
{

    public function index(Request $request)
    {
        $event_id = $request->query('event_id');

        $query = Ride::with(['driver', 'event', 'requests']);

        if ($event_id) {
            $query->where('event_id', $event_id);
        }

        if ($request->has('my_rides') && $request->my_rides) {
            $query->where('driver_id', Auth::id());
        }

        return new RideCollection($query->get());
    }

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

        $event = Event::find($request->event_id);

        $existingRide = Ride::where('event_id', $request->event_id)
            ->where('driver_id', Auth::id())
            ->first();

        if ($existingRide) {
            return response()->json([
                'message' => 'Masz już utworzony przejazd dla tego wydarzenia.'
            ], 422);
        }

        $validated['driver_id'] = Auth::id();
        $ride = Ride::create($validated);

        if (!$event->has_ride_sharing) {
            $event->has_ride_sharing = true;
            $event->save();
        }

        return new RideResource($ride);
    }

    public function show(Ride $ride)
    {
        $ride->load(['driver', 'event', 'requests.passenger']);

        return new RideResource($ride);
    }

    public function update(Request $request, Ride $ride)
    {
        if (Auth::id() !== $ride->driver_id) {
            return response()->json([
                'message' => 'Nie masz uprawnień do edycji tego przejazdu.'
            ], 403);
        }

        $validated = $request->validate([
            'vehicle_description' => 'sometimes|required|string|max:255',
            'available_seats' => 'sometimes|required|integer|min:1',
            'meeting_latitude' => 'sometimes|required|numeric',
            'meeting_longitude' => 'sometimes|required|numeric',
            'meeting_location_name' => 'sometimes|required|string|max:255',
        ]);

        $ride->update($validated);

        return new RideResource($ride);
    }

    public function destroy(Ride $ride)
    {
        if (Auth::id() !== $ride->driver_id) {
            return response()->json([
                'message' => 'Nie masz uprawnień do usunięcia tego przejazdu.'
            ], 403);
        }

        $ride->delete();

        return response()->json(['message' => 'Przejazd został usunięty.']);
    }
}
