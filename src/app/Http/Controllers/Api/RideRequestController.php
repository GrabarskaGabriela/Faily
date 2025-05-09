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
                'message' => 'You need to enter the ride ID.'
            ], 422);
        }

        $ride = Ride::findOrFail($ride_id);

        if (Auth::id() !== $ride->driver_id) {
            return response()->json([
                'message' => 'You do not have permission to view these submissions.'
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
                'message' => 'You can\'t send an application for your own ride.'
            ], 422);
        }

        $existingRequest = RideRequest::where('ride_id', $ride->id)
            ->where('passenger_id', Auth::id())
            ->first();

        if ($existingRequest) {
            return response()->json([
                'message' => 'Already sent an application for this passage.'
            ], 422);
        }

        $existingRequestsCount = RideRequest::where('ride_id', $ride->id)
            ->where('status', 'accepted')
            ->count();

        if ($existingRequestsCount >= $ride->available_seats) {
            return response()->json([
                'message' => 'No seats available on this ride.'
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
                'message' => 'You do not have the authority to manage this request.'
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
                    'message' => 'No seats available on this ride.'
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
                'message' => 'You do not have the authority to cancel this request.'
            ], 403);
        }

        $rideRequest->delete();

        return response()->json(['message' => 'The application has been cancelled.']);
    }

    public function myRequests()
    {
        $requests = RideRequest::with(['ride.driver', 'ride.event'])
            ->where('passenger_id', Auth::id())
            ->get();

        return new RideRequestCollection($requests);
    }
}
