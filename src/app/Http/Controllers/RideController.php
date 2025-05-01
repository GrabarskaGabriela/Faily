<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\Interfaces\RideServiceInterface;
use App\Services\Interfaces\EventServiceInterface;

class RideController extends Controller
{
    protected $rideService;
    protected $eventService;

    public function __construct(RideServiceInterface $rideService, EventServiceInterface $eventService)
    {
        $this->middleware('auth');
        $this->rideService = $rideService;
        $this->eventService = $eventService;
    }

    public function index(Request $request)
    {
        $event_id = $request->get('event_id');

        if (!$event_id) {
            return redirect()->route('events.index')
                ->with('error', 'You must select the event for which you are creating a ride');
        }

        $event = $this->eventService->findById($event_id);

        return redirect()->route('events.create', ['preset_event_id' => $event_id])
            ->with('message', 'Dodaj przejazd dla wydarzenia: ' . $event->title);
    }

    public function create(Request $request)
    {
        $event_id = $request->get('event_id');

        if (!$event_id) {
            return redirect()->route('events.index')
                ->with('error', 'You must select the event for which you are creating a ride');
        }

        $event = $this->eventService->findById($event_id);

        return view('ride.create', compact('event'));
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

        try {
            $ride = $this->rideService->createRide($validated, Auth::id());
            return redirect()->route('events.show', $ride->event_id)
                ->with('success', 'Ride added successfully');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Ride $ride)
    {
        $ride = $this->rideService->getRideWithRelations($ride->id);
        return view('ride.show', compact('ride'));
    }


    public function edit(Ride $ride)
    {
        if (!$this->rideService->canUserManageRide($ride->id, Auth::id())) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', 'You do not have permission to edit this passage.');
        }

        return view('rides.edit', compact('ride'));
    }

    public function update(Request $request, Ride $ride)
    {
        $validated = $request->validate([
            'vehicle_description' => 'required|string|max:255',
            'available_seats' => 'required|integer|min:1',
            'meeting_latitude' => 'required|numeric',
            'meeting_longitude' => 'required|numeric',
            'meeting_location_name' => 'required|string|max:255',
        ]);

        try {
            $this->rideService->updateRide($ride->id, $validated, Auth::id());
            return redirect()->route('rides.show', $ride)
                ->with('success', 'The passage has been updated!');
        } catch (\Exception $e) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Ride $ride)
    {
        try {
            $event_id = $ride->event_id;
            $this->rideService->deleteRide($ride->id, Auth::id());
            return redirect()->route('events.show', $event_id)
                ->with('success', 'The passage has been deleted!');
        } catch (\Exception $e) {
            return redirect()->route('events.show', $ride->event_id)
                ->with('error', $e->getMessage());
        }
    }
}
