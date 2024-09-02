<?php

namespace App\Http\Resources\AreaResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            'uuid' => $this->uuid,
            "title" => $this->title,
            "city" => $this->city->title,
            "count_of_monitors" => $this->count_of_monitors,
            "count_of_delivers" => $this->count_of_delivers,
            "count_of_clients" => $this->count_of_clients,
        ];
    }
}
