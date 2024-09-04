<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource\AddressResource;
use App\Http\Resources\ClientResource\OrderClientResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Address;
use App\Models\Image;
use App\Models\Monitor;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $validator = validator()->make(request()->all(), [
                'status' => 'nullable|in:completed,in_progress,waiting,canceled',
            ]);
            if ($validator->fails()) {
                return $this->ValidationError(null, $validator);
            }
            $query = Order::query()->where("client_id", Auth::User()->id);
            // if (request()->uuid) {
            //     $query->where("uuid", request()->uuid);
            if (request()->has('status')) {
                $query->where('status', request()->status);
            } else {
                $query->whereIn('status', ['in_progress', 'waiting']);
            }
            $orders = $query->get();
            $data['orders'] = OrderClientResource::collection($orders);
            return $this->SuccessResponse($data);
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }

    public function create()
    {
        try {
            $area_id = Auth::User()->area_id;
            $monitors = Monitor::where('area_id', $area_id)->get();
            if ($monitors->count() == 0) {
                return $this->Error("Your area is currently out of service.", 422); // Unprocessable Entity
            }
            $addresses = Auth::user()->addresses;
            $data['addresses'] = AddressResource::collection($addresses);
            return $this->SuccessResponse($data);
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $area_id = Auth::User()->area_id;
            $monitors = Monitor::where('area_id', $area_id)->get();
            if ($monitors->count() == 0) {
                return $this->Error("Your area is currently out of service.", 422); // Unprocessable Entity
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'order' => 'required|string',
                    'address_uuid' => [
                        'required',
                        Rule::exists("addresses", 'uuid')->where('client_id', Auth::user()->id),
                    ],
                    'scheduled_time' => "nullable|date_format:Y-m-d H:i|after_or_equal:today",
                ]
            );
            if ($validator->fails()) {
                return $this->ValidationError($request->all(), $validator);
            }
            $address = Address::where('uuid', $request->address_uuid)->first();
            $order = Order::create([
                "client_id" => Auth::user()->id,
                "order" => $request->order,
                "status" => 'waiting',
                "deliver_id" => null,
                'address_id' => $address->id,
                "scheduled_time" => $request->scheduled_time,
                "estimated_time" => null,
                "start_deliver_time" => null,
                "received_time" => null,
                "canceled" => null,
                "canceled_note" => null,
                "rate" => null,
            ]);
            if ($request->file('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('Orders', $filename);

                $image = new Image();
                $image->url = $path;
                $order->images()->save($image);
                $data['order'] = OrderClientResource::make($order);

                // return $this->PartialContent($data,"image has not been save");
            }
            $data['order'] = OrderClientResource::make($order);
            return $this->SuccessResponse($data);
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
