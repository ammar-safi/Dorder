<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $statusCode = 200;
       
        $city =CityResource::collection(City::all()) ;
        // dd($city) ;
        if ($city) {
            $data = [
                'data' => $city,
                'status' => True,
                'error' => null,
                'statusCode' => $statusCode,
            ];
        } else {
            $data = [
                'data' => '',
                'status' => false,
                'error' => 'لا يوجد مدن للعرض',
                'statusCode' => $statusCode,
            ];
        }


        return response($data, $statusCode);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $validate = Validator::make(
            [$id],
            [
                'id' => 'required|exists:cities,id',
            ],
            [
                'id.required' => "حصل خطا",
                "id.exists"   => 'المدينة غير موجودة'
            ]
        );

        $statusCode = 200;
        $data = [
            'data' => '',
            'status' => False,
            'error',
            'statusCode' => $statusCode,
        ];


        if ($validate->fails()) {
            $city = City::find($id);
            if ($city) {
                $data = [
                    'data' => $city,
                    'status' => True,
                    'error',
                    'statusCode' => $statusCode,
                ];
            } else {
                $data = [
                    'data' => '',
                    'status' => false,
                    'error' => 'error',
                    'statusCode' => $statusCode,
                ];
            }
        }

        return response($data, $statusCode);
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
