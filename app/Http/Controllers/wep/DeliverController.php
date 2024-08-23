<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Deliver;
use App\Models\Monitor;
use App\Models\User;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Symfony\Contracts\Service\Attribute\Required;

class DeliverController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth", "hasAccess"]);
        // $this->middleware("isAdmin")->except("index");
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $flag = 'delivers-show';
        try {

            $validate = Validator::make(
                $request->all(),
                [
                    "city_id" => "exists:cities,id",
                    "area_id" => "exists:areas,id",
                ],
                [
                    "city_id.exists" => "حدث حطأ , حاول مرا اخرى",
                    "area_id.exists" => "حدث حطأ , حاول مرا اخرى",
                ]
            );

            if ($validate->fails()) {
                return back()->withErrors($validate)->withInput($request->all());
            }



            $flag = "deliver-show";

            $cities = City::all();

            $query = Deliver::query();
            // dd($request->input("city_id"));
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
                $query->whereIn('deliver_id', User::where("type", "deliver")->where("name", 'LIKE', "%{$searchName}%")->pluck('id')->toArray());
            }

            if ($selectedCityId || $selectedAreaId || $searchName) {
                $delivers = $query->get();
            } else {
                $delivers = null;
            }
            return view("panel.dashboard.delivers.delivers", compact("flag", "delivers", "cities", "areas", 'selectedCityId', 'selectedAreaId', 'searchName'));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flag = '';
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
        $flag = '';
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $flag = 'delivers-edit';
        $validator = Validator::make(
            $request->all(),
            [
                'id' => "required|exists:delivers,id",
                "city_id" => "nullable|exists:cities,id",
                "area_id" => "exists:areas,id",
            ],
            [
                'id.required' => "حصل خطأ غير متوقع",
                'id.exist' => "حصل خطأ غير متوقع",
                "city_id.exists" => "",
                "area_id.exists" => "",

            ]
        );
        // dd($request->);
        if ($validator->fails()) {
            // dd("test");
            return back()->withErrors($validator)->withInput($request->all());
        }

        try {

            $deliver = Deliver::find($request->id);
            if (!$deliver) {
                return back()->with("error", "حدث خطأ , يرجى المحاولة لاحقا");
            }
            $cities = city::all();
            $areas = Area::where("city_id", $request->input("city_id") ? $request->input("city_id") : $deliver->area->city->id)->get();
            // dd($request->input("area_id"));
            $selectedCityId = $request->input("city_id") ? $request->input("city_id") : $deliver->area->city->id;
            $selectedAreaId = $request->input("area_id") ? $request->input("area_id") : $deliver->area->id;

            return view("panel.dashboard.delivers.edit", compact('flag', 'deliver', 'cities', "areas", 'selectedCityId', 'selectedAreaId'));
        } catch (Exception $e) {
            return back()->with("error", "حدث خطأ , يرجى المحاولة لاحقا");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'id' => "required|exists:delivers,id",
                'name' => 'required|string|regex:/^[\p{Arabic}\s]+$/u|max:255',
                'city_id' => 'required|exists:cities,id',
                'area_id' => [
                    'required',
                    Rule::exists('areas', 'id')->where(function (Builder $query) use ($request) {
                        return $query->where('city_id', $request->city_id);
                    }),

                ],
            ],
            [
                'id.required' => "حصل خطأ غير متوقع",
                'id.exists' => "حصل خطأ غير متوقع",

                'name.required' => 'يرجى إدخال الاسم.',
                'name.string' => 'يجب أن يكون الاسم نصيًا.',
                'name.regex' => 'يجب أن يحتوي الاسم على أحرف عربية فقط.',
                'name.max' => 'يجب أن لا يتجاوز الاسم 255 حرفًا.',

                'city_id.required' => 'يرجى تحديد المدينة.',
                'city_id.exists' => 'المدينة المحددة غير موجودة.',

                'area_id.required' => 'يرجى تحديد المنطقة.',
                'area_id.exists' => 'المنطقة المحددة غير موجودة ضمن المدينة المحددة.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->all());
        }

        try {

            $deliver = Deliver::find($request->id);

            if (!$deliver) {
                return back()->with('error', 'المشرف غير موجود.');
            }

            if ($deliver->update(['area_id' => $request->area_id,]) && $deliver->User->update(['name' => $request->name])) {
                return redirect()->route("delivers.show")->with("success", 'تم التعديل بنجاح');
            } else {
                return back()->with('error', 'لم يتم التعديل , حاول مرة اخرى');
            }
        } catch (Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تحديث المشرف. يرجى المحاولة لاحقاً.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        //
    }
    /**
     * Soft-Delete the specified resource from storage.
     */
    public function delete()
    {
        //
    }

    
}
