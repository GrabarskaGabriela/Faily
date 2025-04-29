<?php

namespace App\Http\Controllers;

use App\Models\RideRequest;
use App\Models\Ride;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\RideRequestServiceInterface;
use App\Services\Interfaces\RideServiceInterface;
use App\Notifications\RideRequestStatusChanged;

class RideRequestController extends Controller
{
    protected $rideRequestService;
    protected $rideService;

    public function __construct(
        RideRequestServiceInterface $rideRequestService,
        RideServiceInterface $rideService
    ) {
        $this->middleware('auth');
        $this->rideRequestService = $rideRequestService;
        $this->rideService = $rideService;
    }

    public function index(Request $request)
    {
        $ride_id = $request->query('ride_id');

        if (!$ride_id) {
            return redirect()->route('events.index');
        }

        try {
            $data = $this->rideRequestService->getRideRequestsForDriver($ride_id, Auth::id());
            return view('ride_requests.index', [
                'requests' => $data['requests'],
                'ride' => $data['ride']
            ]);
        } catch (\Exception $e) {
            $ride = $this->rideService->findById($ride_id);
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        $ride_id = $request->query('ride_id');

        if (!$ride_id) {
            return redirect()->route('events.index');
        }

        $ride = $this->rideService->getRideWithRelations($ride_id);

        $result = $this->rideRequestService->canUserRequestRide($ride, Auth::id());

        if (!$result['canRequest']) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', $result['message']);
        }

        return view('ride_requests.create', compact('ride'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ride_id' => 'required|exists:rides,id',
            'message' => 'nullable|string',
        ]);

        try {
            $rideRequest = $this->rideRequestService->createRequest($validated, Auth::id());
            $ride = $this->rideService->findById($rideRequest->ride_id);

            return redirect()->route('events.show', $ride->event_id)
                ->with('success', 'Your application has been sent!');
        } catch (\Exception $e) {
            $ride = $this->rideService->findById($validated['ride_id']);
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', $e->getMessage());
        }
    }

    public function update(Request $request, RideRequest $rideRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        try {
            $this->rideRequestService->updateRequestStatus($rideRequest, $validated['status'], Auth::id());

            return redirect()->route('ride_requests.index', ['ride_id' => $rideRequest->ride_id])
                ->with('success', 'Status has be updated!');
        } catch (\Exception $e) {
            $ride = $this->rideService->findById($rideRequest->ride_id);
            return redirect()->route('ride_requests.index', ['ride_id' => $rideRequest->ride_id])
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(RideRequest $rideRequest)
    {
        try {
            $ride = $this->rideService->findById($rideRequest->ride_id);
            $this->rideRequestService->cancelRequest($rideRequest, Auth::id());

            return redirect()->route('events.show', $ride->event_id)
                ->with('success', 'The application has been canceled!');
        } catch (\Exception $e) {
            $ride = $this->rideService->findById($rideRequest->ride_id);
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', $e->getMessage());
        }
    }
}
