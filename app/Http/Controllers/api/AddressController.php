<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource\AddressResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Address;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddressController extends Controller
{
    use GeneralTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $client = Auth::user();
            if ($client && $client->type == "client") {
                $addresses = $client->addresses;
                $data["addresses"] = AddressResource::collection($addresses);
                return $this->SuccessResponse($data);
            } else {
                return $this->Forbidden();
            }
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $uuid = Auth::user()->uuid;
            $validate = Validator::make(
                $request->all(),
                [
                    'title' => 'required|string|max:255|unique:addresses,title',
                ],
            );
            if ($validate->fails()) {
                return $this->ValidationError($request->all(), $validate);
            }

            $client = Auth::user();
            if ($client && $client->type == "client") {
                $addresses = Address::create([
                    'client_id' => Auth::user()->id,
                    'title' => $request->title
                ]);
                $data["addresses"] = AddressResource::make($addresses);
                return $this->SuccessResponse($data);
            } else {
                return $this->Forbidden();
            }
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
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
