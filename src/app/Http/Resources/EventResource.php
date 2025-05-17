<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
class EventResource extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'location_name' => $this->location_name,
            'people_count' => $this->people_count,
            'has_ride_sharing' => $this->has_ride_sharing,
            'available_spots' => $this->whenLoaded('acceptedAttendees', function () {
                return $this->getAvailableSpotsCount();
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'photos' => PhotoResource::collection($this->whenLoaded('photos')),
            'attendees' => EventAttendeeResource::collection($this->whenLoaded('attendees')),
            'rides' => RideResource::collection($this->whenLoaded('rides')),
            'is_attending' => $request->user() ? $this->isUserAttending($request->user()->id) : false,
        ];
    }
}
