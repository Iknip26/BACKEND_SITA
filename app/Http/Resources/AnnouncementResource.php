<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnnouncementResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            "title" => $this->title,
            "detail" => $this->detail,
            "attachment" => $this->attachment,
            "created_at" => $this->created_at
        ];
    }
}
