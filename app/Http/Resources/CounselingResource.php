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
            "mahasiswa" => new StudentResource($this->whenLoaded('student')),
            "Dosen Pembimbing 1" => new LecturerResource($this->whenLoaded('lecturer')),
            "project" => new ProjectResource($this->whenLoaded('project'))
        ];
    }
}
