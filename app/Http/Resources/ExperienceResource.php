<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            'student_id' => $this->student_id,
            'position' => $this->position,
            'company_name' => $this->company_name,
            'field' => $this->field,
            'duration' => $this->duration,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'student' => new StudentResource($this->whenLoaded('student')),
        ];
    }
}
