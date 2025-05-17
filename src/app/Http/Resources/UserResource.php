<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'age' => $this->age,
            'phone' => $this->phone,
            'description' => $this->description,
            'photo_url' => $this->photo_path ? asset('storage/' . $this->photo_path) : null,
            'language' => $this->language,
            'theme' => $this->theme,
            'email_notifications' => $this->email_notifications,
            'two_factor_enabled' => $this->two_factor_enabled,
            'photo_updated_at' => $this->photo_updated_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
