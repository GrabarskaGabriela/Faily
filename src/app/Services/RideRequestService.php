<?php

namespace App\Services;

use App\Models\Ride;
use App\Models\RideRequest;
use App\Notifications\RideRequestStatusChanged;
use App\Repositories\Interfaces\RideRepositoryInterface;
use App\Repositories\Interfaces\RideRequestRepositoryInterface;
use App\Services\Interfaces\RideRequestServiceInterface;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Facades\Activity;

class RideRequestService extends BaseService implements RideRequestServiceInterface
{
    protected $repository;

    protected $rideRepository;


    public function __construct(
        RideRequestRepositoryInterface $repository,
        RideRepositoryInterface $rideRepository
    ) {
        $this->repository = $repository;
        $this->rideRepository = $rideRepository;
    }

    public function getRideRequestsForDriver($rideId, $userId)
    {
        $ride = $this->rideRepository->find($rideId);

        if ($userId !== $ride->driver_id) {
            throw new \Exception('You do not have permission to view these requests.');
        }

        return [
            'requests' => $this->repository->getRideRequests($rideId),
            'ride' => $ride
        ];
    }

    public function canUserRequestRide(Ride $ride, $userId)
    {
        $result = [
            'canRequest' => true,
            'message' => null
        ];

        if ($userId === $ride->driver_id) {
            $result['canRequest'] = false;
            $result['message'] = "You can't send an application to your own ride.";
            return $result;
        }

        if ($this->repository->hasUserRequested($ride->id, $userId)) {
            $result['canRequest'] = false;
            $result['message'] = 'You have already sent an application for this passage.';
            return $result;
        }

        return $result;
    }

    public function createRequest(array $data, $userId)
    {
        $ride = $this->rideRepository->find($data['ride_id']);

        if ($userId === $ride->driver_id) {
            throw new \Exception("You can't send an application to your own ride.");
        }

        $acceptedRequests = $this->repository->countAcceptedRequests($ride->id);
        if ($acceptedRequests >= $ride->available_seats) {
            throw new \Exception('No available seats on this ride.');
        }

        $requestData = [
            'ride_id' => $data['ride_id'],
            'passenger_id' => $userId,
            'status' => 'pending',
            'message' => $data['message'] ?? null,
        ];

        $rideRequest = $this->repository->create($requestData);

        $ride->driver->notify(new RideRequestStatusChanged($rideRequest, $ride, Auth::user()));

        return $rideRequest;
    }

    public function updateRequestStatus(RideRequest $rideRequest, $status, $userId)
    {
        $ride = $this->rideRepository->find($rideRequest->ride_id);

        if ($userId !== $ride->driver_id) {
            throw new \Exception('You do not have the authority to manage this request.');
        }

        if ($status === 'accepted') {
            $acceptedCount = $this->repository->countAcceptedRequests($ride->id);
            if ($acceptedCount >= $ride->available_seats) {
                throw new \Exception('No seats available on this ride.');
            }
        }

        $oldStatus = $rideRequest->status;
        $rideRequest = $this->repository->update($rideRequest->id, ['status' => $status]);

        $rideRequest->passenger->notify(new RideRequestStatusChanged($rideRequest, $ride));

        $passengerName = $rideRequest->passenger->name ?? 'Passenger';
        $driverName = $ride->driver->name ?? 'Driver';
        $eventTitle = $ride->event->title ?? 'Event';

        Activity::performedOn($rideRequest)
            ->withProperties([
                'old_status' => $oldStatus,
                'new_status' => $status,
                'ride_id' => $ride->id,
                'event_id' => $ride->event_id,
                'event_title' => $eventTitle,
                'driver_id' => $ride->driver_id,
                'passenger_id' => $rideRequest->passenger_id
            ])
            ->log("The status of the travel request from {$passengerName} to {$driverName} for the event \"{$eventTitle}\" changed from \"{$oldStatus}\" to \"{$status}\"");

        return $rideRequest;
    }

    public function cancelRequest(RideRequest $rideRequest, $userId)
    {
        if ($userId !== $rideRequest->passenger_id) {
            throw new \Exception('You do not have the authority to cancel this request.');
        }

        return $this->repository->delete($rideRequest->id);
    }
}
