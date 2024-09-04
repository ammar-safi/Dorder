<?php

namespace App\Http\Resources\ClientResource;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class OrderClientResource extends JsonResource
{
    public function pathImages($images)
    {
        $file = [];
        foreach ($images as $image) {
            $file[] = Storage::url( 'app/' . $image->url);
        }
        return ($file);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd(empty($this->images));
        if (!$this->canceled) {
            /* If Order Not Canceled */
            if ($this->status == 'completed') {

                return [
                    [
                        'uuid' => $this->uuid,
                        "order_details" => $this->order,
                        'image' => $this->images ? Storage::url("public/" . $this->image->url) : "Order has no image",
                        "deliver" => [
                            "deliver_uuid" => $this->deliver->uuid,
                            "deliver_name" => $this->deliver->name,
                        ],
                        'address' => $this->address->title,
                        "scheduled_time" => $this->scheduled_time,
                        "estimated_time" => $this->estimated_time,
                        "start_deliver_time" => $this->start_deliver_time,
                        "received_time" => $this->received_time,
                        "rate" => $this->rate != NULL ? $this->rate : 'there is no rate',
                        'status' => $this->status,

                    ]
                ];
            } elseif ($this->status == 'in_progress') {
                return [
                    [
                        'uuid' => $this->uuid,
                        "order_details" => $this->order,
                        'image' => $this->images ? Storage::url("public/" . $this->image->url) : "Order has no image",
                        "deliver" => [
                            "deliver_uuid" => $this->deliver->uuid,
                            "deliver_name" => $this->deliver->name,
                        ],
                        'address' => $this->address->title,
                        "scheduled_time" => $this->scheduled_time,
                        "estimated_time" => $this->estimated_time,
                        "start_deliver_time" => $this->start_deliver_time,
                        'status' => $this->status,

                    ]
                ];
            } else  /* waiting */ {

                return [

                    'uuid' => $this->uuid,
                    "order_details" => $this->order,
                    'image' =>  !empty($this->images) ? $this->pathImages($this->images) : "Order has no image",
                    'address' => $this->address->title,
                    "scheduled_time" => $this->scheduled_time,
                    'status' => $this->status,

                ];
            }

            /* If Order Not Canceled */
        } else {
            return [
                'uuid' => $this->uuid,
                "order_details" => $this->order,
                'image' => $this->images ? Storage::url("public/" . $this->image->url) : "Order has no image",
                'address' => $this->address->title,
                'canceled' => $this->canceled,
                'canceled_note' => $this->canceled_note,
                "deliver" => $this->deliver ?
                    [
                        "deliver_uuid" => $this->deliver->uuid,
                        "deliver_name" => $this->deliver->name,
                    ]
                    : NULL,
                'status' => $this->status,

            ];
        }
    }
}
