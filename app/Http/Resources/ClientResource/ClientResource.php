<?php

namespace App\Http\Resources\ClientResource;

use App\Http\Resources\PackageResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ClientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->uuid);
        return [
            'name' => $this->name,
            'email' => $this->email,
            'profile_image' => $this->image?Storage::url("public/" . $this->image->url ):"client has no image",
            'mobile' => $this->mobile,
            'area' => $this->area ? $this->area->title : "client has no area",
            'city' => $this->area?($this->area->city ? $this->area->city->title : "client has no city"):"client has no city",
            "active" => $this->active?"client active":"client not active",
            'subscription_fees' => $this->subscription_fees,
            'expire' => $this->expire,
            "package" => PackageResource::make($this->package),

        ];
    }
}
