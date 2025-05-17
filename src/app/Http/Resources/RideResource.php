<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RideResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_id' => $this->event_id,
            'driver_id' => $this->driver_id,
            'vehicle_description' => $this->vehicle_description,
            'available_seats' => $this->available_seats,
            'meeting_latitude' => $this->meeting_latitude,
            'meeting_longitude' => $this->meeting_longitude,
            'meeting_location_name' => $this->meeting_location_name,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'driver' => new UserResource($this->whenLoaded('driver')),
            'event' => new EventResource($this->whenLoaded('event')),
            'requests' => RideRequestResource::collection($this->whenLoaded('requests')),
            'taken_seats' => $this->whenLoaded('requests', function () {
                return $this->requests->where('status', 'accepted')->count();
            }, 0),
        ];
    }
}
