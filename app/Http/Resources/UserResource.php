<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'role' => $this->role,
            'login_status' => $this->login_status,
            'last_login' => $this->last_login,
            'profile_photo' => $this->profile_photo,
            'email' => $this->email,
            'student' => new StudentResource($this->whenLoaded('student'))
        ];
    }
}
