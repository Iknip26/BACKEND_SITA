<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request): array
    {
        // dd($this);
        return [
            'id' => $this->id,
            'user' => new UserResource($this->whenLoaded('user')),
            'NIM' => $this->NIM,
            'semester' => $this->semester,
            'IPK' => $this->IPK,
            'SKS' => $this->SKS,
            'phone_number' => $this->phone_number,
            'link_github' => $this->link_github,
            'link_porto' => $this->link_porto,
            'link_linkedin' => $this->link_linkedin,
            'skills' => SkillResource::collection($this->whenLoaded('skills')),
            'achievement' => AchievementResource::collection($this->whenLoaded('achievements'))
        ];
    }
}
