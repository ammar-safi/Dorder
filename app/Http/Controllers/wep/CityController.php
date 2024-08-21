<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{

    public function __construct()
    {
        $this->middleware(["auth", "hasAccess"]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flag = "show-cities";
        $cities = City::all();
        return view("panel.dashboard.cities.cities", compact("flag", "cities"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $add = $request->add ? $request->add : Null;
        return view("panel.dashboard.cities.add", ["flag" => "add-city", 'add' => $add]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            "title" => $request->title,
        ];
        $rules = [
            "title" => "required|string|regex:/^[\p{Arabic}\s]+$/u",
        ];
        $messages = [
            "title.required" => "عليك اضافة اسم مدينة",
            "title.string" => "الاسم الذي ادخلته غير صحيح",
            "title.regex" => "الاسم الذي ادخلته يجب ان يكون باللغة العربية",
        ];
        $validate = Validator::make($data, $rules, $messages);

        if ($validate->fails()) {
            return redirect()->Route("cities.add")->withInput($request->all())->withErrors($validate);
        }

        $store = City::create($data);
        if ($store) {
            return redirect()->route("cities.add", ['add' => true]);
        }
        return redirect()->route("cities.add", ['add' => false]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $flag = "show-cities";

        $data = ['id' => $request->input('id')];
        $rules = [
            'id' => "required",
        ];
        $messages = [
            'id.required' => " 😢 طلب خاطئ , حاول مرة اخرى",
        ];
        $validate = Validator::make($data, $rules, $messages);
        if ($validate->fails()) {
            return redirect()->Route($request->route)->withInput($request->all())->withErrors($validate);
        }
        $city = City::find($request->id);
        if ($city) {
            $route = $request->route;
            return view("panel.dashboard.cities.update", compact("city", "flag", "route"));
        }
        return redirect()->Route($request->route)->with("error", "طلب خاطئ , حاول مرة اخرى 😢 ");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id' => "required",
                "title" => "required|string|regex:/^[\p{Arabic}\s]+$/u",
            ],
            [
                'id.required' => " 😢 طلب خاطئ , حاول مرة اخرى",
                'id.id' => " 😢 خطأ غير متوقع ! , حاول مرة اخرى",

                "title.required" => "عليك اضافة اسم مدينة",
                "title.string" => "الاسم الذي ادخلته غير صحيح",
                "title.regex" => "الاسم الذي ادخلته يجب ان يحنوي على حروف  باللغة العربية فقط",
            ]
        );
        if ($validate->fails()) {
            // return Route('cities.show')->with('error' , "حصل خطأ غير متوقع")
            return back()->withInput($request->all())->withErrors($validate);
        }

        $city = City::find($request->id);
        if ($city && $city->update(["title" => $request->title])) {
            return redirect()->route($request->route);
        } else {
            session()->flash('error', "حصل خطأ غير متوقع");
            return back();
        }
    }

    /**
     * Soft delete for a specified resource .
     */
    public function delete(Request $request)
    {

        $validate = Validator::make($request->all(), ['id' => "required"], [
            'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة"
        ]);
        if ($validate->fails()) {
            /**
             * اذا ضرب شي ايرور شيل التحت وفك التغليق عن الفوقا 
             */
            // return redirect()->route($request->route)->withErrors($validate);
            return back()->withErrors($validate);
        }


        $city = City::find($request->id);
        if ($city) {

            foreach ($city->areas as $area) {
                $area->Monitors()->delete();
                $area->Delivers()->delete();
                $area->Users()->delete();
            }
            $city->areas()->delete();
            $city->delete();
            return back();
        }
        return back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function conformAdding(Request $request)
    {
        $data = [
            "title" => $request->title,
        ];
        $rules = [
            "title" => "required|string|regex:/^[\p{Arabic}\s]+$/u",
        ];
        $messages = [
            "title.required" => "عليك اضافة اسم مدينة",
            "title.string" => "الاسم الذي ادخلته غير صحيح",
            "title.regex" => "الاسم الذي ادخلته يجب ان يكون باللغة العربية",
        ];
        $validate = Validator::make($data, $rules, $messages);

        if ($validate->fails()) {
            return redirect()->Route("cities.add")->withInput($request->all())->withErrors($validate);
        }

        $title = $request->title;
        $is_exist = City::where("title", "LIKE", "%{$title}%")->orWhere("title", "LIKE", "{$title}%")->orWhere("title", "LIKE", "%{$title}")->first();
        return view("panel.dashboard.cities.addCity", ["flag" => "add-city", "request" => $request, 'is_exist' => $is_exist]);
    }
    public function showEdit()
    {
        $flag = 'edit-city';
        $cities = City::all();
        return view("panel.dashboard.cities.edit", compact('flag', 'cities'));
    }
}
