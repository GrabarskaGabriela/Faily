<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RideRequestResource;
use App\Http\Resources\RideRequestCollection;
use App\Models\RideRequest;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RideRequestController extends Controller
{
    public function index(Request $request)
    {
        $ride_id = $request->query('ride_id');

        if (!$ride_id) {
            return response()->json([
                'message' => 'Musisz podać ID przejazdu.'
            ], 422);
        }

        $ride = Ride::findOrFail($ride_id);

        if (Auth::id() !== $ride->driver_id) {
            return response()->json([
                'message' => 'Nie masz uprawnień do przeglądania tych zgłoszeń.'
            ], 403);
        }

        $requests = RideRequest::where('ride_id', $ride_id)
            ->with('passenger')
            ->get();

        return new RideRequestCollection($requests);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'message' => 'nullable|string',
        ]);

        $ride = Ride::findOrFail($request->ride_id);

        if (Auth::id() === $ride->driver_id) {
            return response()->json([
                'message' => 'Nie możesz wysłać zgłoszenia do własnego przejazdu.'
            ], 422);
        }

        $existingRequest = RideRequest::where('ride_id', $ride->id)
            ->where('passenger_id', Auth::id())
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'Już wysłałeś zgłoszenie do tego przejazdu.'
            ], 422);
        }

        $existingRequestsCount = RideRequest::where('ride_id', $ride->id)
            ->where('status', 'accepted')
            ->count();

        if ($existingRequestsCount >= $ride->available_seats) {
            return response()->json([
                'message' => 'Brak dostępnych miejsc w tym przejeździe.'
            ], 422);
        }

        $validated['passenger_id'] = Auth::id();
        $validated['status'] = 'pending';

        $rideRequest = RideRequest::create($validated);

        return new RideRequestResource($rideRequest);
    }

    public function update(Request $request, RideRequest $rideRequest)
    {
        $ride = Ride::findOrFail($rideRequest->ride_id);

        if (Auth::id() !== $ride->driver_id) {
            return response()->json([
                'message' => 'Nie masz uprawnień do zarządzania tym zgłoszeniem.'
            ], 403);
        }

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        if ($request->status === 'accepted') {
            $acceptedCount = RideRequest::where('ride_id', $ride->id)
                ->where('status', 'accepted')
                ->count();

            if ($acceptedCount >= $ride->available_seats) {
                return response()->json([
                    'message' => 'Brak dostępnych miejsc w tym przejeździe.'
                ], 422);
            }
        }

        $rideRequest->update($validated);

        return new RideRequestResource($rideRequest);
    }

    public function destroy(RideRequest $rideRequest)
    {
        if (Auth::id() !== $rideRequest->passenger_id) {
            return response()->json([
                'message' => 'Nie masz uprawnień do anulowania tego zgłoszenia.'
            ], 403);
        }

        $rideRequest->delete();

        return response()->json(['message' => 'Zgłoszenie zostało anulowane.']);
    }

    public function myRequests()
    {
        $requests = RideRequest::with(['ride.driver', 'ride.event'])
            ->where('passenger_id', Auth::id())
            ->get();

        return new RideRequestCollection($requests);
    }
}
