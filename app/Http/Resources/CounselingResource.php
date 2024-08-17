<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CounselingResource extends JsonResource
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
            "date" => $this->date,
            "subject" => $this->subject,
            "description" => $this->description,
            "lecturer note" => $this->lecturer_note,
            "file" => $this->file,
            "progress" => $this->progress,
            "status" => $this->status,
            "project_count" => $this->getCountedProject(),
            "project" => new ProjectResource($this->whenLoaded('project'))
        ];
    }
}
