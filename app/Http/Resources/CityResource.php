<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'count_of_areas' => $this->count_of_areas,
            'count_of_clients' => $this->count_of_clients,
            "count_of_monitors" => $this->count_of_monitors,
            "count_of_delivers" => $this->count_of_delivers,
            'areas' => AreaResource::collection($this->areas) ,
        ];
    }
}
