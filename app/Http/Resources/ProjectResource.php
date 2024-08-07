<?php

namespace App\Http\Resources;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class ProjectResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "agency" => $this->agency,
            "description" => $this->description,
            "tools" => $this->tools,
            "status" => $this->status,
            "Approval" => $this->Approval,
            'instance' => $this->instance,
            "lecturer" => new LecturerResource($this->whenLoaded('lecturer'))
        ];
    }
}
