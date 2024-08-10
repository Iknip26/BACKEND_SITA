<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PeriodResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray( $request): array
    {
        return [
            "semester" => $this->semester,
            "year" => $this->year,
            "status" => $this->status,
            "start date" => $this->start_date,
            "end date" => $this->end_date,
        ];
    }
}
