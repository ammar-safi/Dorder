<?php

namespace App\Http\Resources\ClientResource;

use App\Http\Resources\CityResource\CityResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EditProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->image ? Storage::url("public/" . $this->image->url) : "client has no image",
            'mobile' => $this->mobile,
            'area' => $this->area ? $this->area->title : "client has no area",
            'city' => $this->area->city ? $this->area->city->title : "client has no city",

        ];
    }
}
