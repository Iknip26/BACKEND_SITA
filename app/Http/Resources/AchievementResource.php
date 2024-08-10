<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AchievementResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'achievement name' => $this->achievement_name,
            'achievement type' => $this->achievement_type,
            'achievement level' => $this->achievement_level,
            'achievement year' => $this->achievement_year,
            'description'
        ];
    }
}
