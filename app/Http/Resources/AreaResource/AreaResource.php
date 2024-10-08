<?php

namespace App\Http\Resources\AreaResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'city_uuid' => $this->city->uuid,
            'title' => $this->title,
        ];
    }
}
