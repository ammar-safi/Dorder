<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AreaResource\AreaResource;
use App\Http\Resources\CityResource\CityResource;
use App\Http\Resources\ClientResource\ClientResource;
use App\Http\Resources\ClientResource\EditProfileResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Area;
use App\Models\City;
use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    use GeneralTrait;

    function __construct()
    {
        $this->middleware('auth:sanctum');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $client = Auth::User();
            $data['client'] = ClientResource::make($client);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        try {
            $client = Auth::User();
            $data['client'] = EditProfileResource::make($client);
            $data['areas'] = AreaResource::collection(Area::all());
            $data['cities'] = CityResource::collection(City::all());
            return $this->SuccessResponse($data);
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $uuid = Auth::user()->uuid;
            $validate = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'email' => [
                        'required',
                        'email',
                        'max:255',
                        Rule::unique("users", "email")->where('type', "client")->ignore($uuid, "uuid")
                    ],
                    'mobile' => [
                        'required',
                        'string',
                        'regex:/^09[0-9]{8}$/',
                        Rule::unique("users", "mobile")->where('type', "client")->ignore($uuid, "uuid")
                    ],
                    'area_uuid' => 'nullable|exists:areas,uuid',
                    'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ],
            );
            if ($validate->fails()) {
                return $this->ValidationError($request->all(), $validate);
            }
            // dd($request->all);

            $client = Auth::user();
            if ($client && $client->type == "client") {
                $client->name = $request->name;
                $client->mobile = $request->mobile;
                $client->email = $request->email;
                $client->area_id = $request->area_id;
                $client->save();
                if ($request->input('profile_image')) {
                    if ($client->image && Storage::exists('public/' . $client->image->url)) {
                        Storage::delete('public/' . $client->image->url);
                        $client->image->delete();
                    }
                    $file = $request->file('profile_image');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('clients', $filename, 'public');

                    $image = new Image();
                    $image->url = $path;

                    if (!$client->image()->save($image)) {
                        return $this->PartialUpdate("profile image has not been updated");
                    }
                }
                // dd($client);
                return $this->SuccessResponse();
            } else {
                return $this->Forbidden();
            }
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
