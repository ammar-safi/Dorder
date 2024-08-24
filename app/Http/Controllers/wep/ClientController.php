<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\User;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "hasAccess"]);
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
                    "city_id" => "exists:cities,id",
                    'area_id' => [
                        Rule::exists('areas', 'id')->where(function (Builder $query) use ($request) {
                            return $query->where('city_id', $request->city_id);
                        }),

                    ]
                ],
                [
                    "city_id.exists" => "حدث حطأ , حاول مرا اخرى",
                    "area_id.exists" => "حدث حطأ , حاول مرا اخرى",
                ]
            );
            if ($validate->fails()) {
                return redirect()->back()->with("error" , 'حدث خطأ اثناء البحث, حاول مرا اخرى')->withErrors($validate->errors());;
            }
            $flag = 'clients-show' ;
            $cities = City::all();
            $query = User::query();

            $searchName = $request->input('search_name');
            $selectedCityId = $request->input("city_id");
            $selectedAreaId = $request->input("area_id");
            $areas = $selectedCityId ? Area::where('city_id', $selectedCityId)->get() : '';

            if ($selectedAreaId) {
                $query->where("area_id", $selectedAreaId);
            } elseif ($selectedCityId) {
                $query->whereIn("area_id", (Area::where("city_id", $selectedCityId)->pluck("id")->toArray()));
            }
            if ($searchName) {
                $query->where("name", "like", "%" . $searchName . "%");
            }

            if ($selectedAreaId || $selectedCityId || $searchName) {
                $clients = $query->where("type", 'client')->get();
            } else {
                $clients = User::Where("type", 'client')->get();
            }
            return view('panel.dashboard.clients.clients', compact('clients', 'cities', 'areas', 'flag', 'selectedCityId', 'selectedAreaId', 'searchName'));

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete(Request $request)
    {
        try {
            $id = $request->id;
            $client = User::Where("type", 'client')->where("id", $id)->first();
            if ($client->delete()) {
                return redirect()->back()->with('success', 'Client deleted successfully');
            }

            return redirect()->back()->with('error', 'Something went wrong');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong');
        }
    }
}
