<?php

namespace App\Http\Resources\ClientResource;

use App\Http\Resources\PackageResource;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->active) {

            return [
                'name' => $this->name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'uuid' => $this->uuid,
                'package' => PackageResource::make($this->package),
                'subscription_fees' => $this->subscription_fees,
                'active' => $this->active ? "العميل مشترك" : " العميل غير مشتر",
                'expire' => $this->expire,
                'Location' => $this->area ? [
                    "city" => $this->area->city->title,
                    "area" => $this->area->title
                ] : "لا يوجد",

            ];
        } else {
            return [
                'name' => $this->name,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'uuid' => $this->uuid,
                'area' => $this->area ? $this->area->title : "لا يوجد" ,
                "subscribe" => "العميل لم يشترك يعد",
            ];
        }
    }
}
