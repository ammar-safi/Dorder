<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
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
            'title' => $this->title,
            'price' => $this->price,
            'count_of_orders' => $this->count_of_orders,
            'package_price' => $this->package_price,
            'order_price' => $this->order_price,
        ];
    }
}
