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
            "Approval_dosen 1" => $this->Approval_lecturer_1,
            "Approval_dosen 2" => $this->Approval_lecturer_2,
            "Approval_kaprodi" => $this->Approval_kaprodi,
            'instance' => $this->instance,
            "year" => $this->year,
            'uploadedBy' => $this->uploadedBy,
            "Dosen_1" => new LecturerResource($this->whenLoaded('lecturer1')),
            "Dosen_2" => new LecturerResource($this->whenLoaded('lecturer2')),
            "Created_at" => $this->created_at
        ];
    }
}
