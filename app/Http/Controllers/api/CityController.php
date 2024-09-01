<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource\CityDataResource;
use App\Http\Resources\CityResource\CityResource;
use App\Http\Resources\CityResource\CityUUIDResource;
use App\Http\Traits\GeneralTrait;
use App\Models\City;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CityController extends Controller
{
    use GeneralTrait;

    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'search' => 'nullable|string|max:100',
                ]
            );

            if ($validate->fails()) {
                return $this->validateError($validate);
            }

            $query = City::query();
            if ($request->input('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            $cities = $query->get();
            $data["collection"] = CityDataResource::collection($cities);

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
            $validate = Validator::make($request->all(), ['title' => 'required|string|max:100|unique:cities,title']);
            if ($validate->fails()) {
                return $this->validationError($validate);
            }

            $city = City::create(['title' => $request->title , 'uuid']);
            return $this->SuccessResponse(CityResource::make($city));
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

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
