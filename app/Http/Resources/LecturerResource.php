<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LecturerResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'front_title' => $this->front_title,
            'back_title' => $this->back_title,
            'NID' => $this->NID,
            'max_quota' => $this->max_quota,
            'phone_number' => $this->phone_number,
        ];
    }
}
