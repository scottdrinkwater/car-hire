<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "from_date" => $this->from_date,
            "to_date" => $this->to_date,
            "cost" => $this->cost,
            "car" => [
                "id" => $this->car->id,
                "model" => $this->car->model,
                "cost" => $this->car->cost,
                "registration" => $this->car->registration,
            ],
        ];
    }
}
