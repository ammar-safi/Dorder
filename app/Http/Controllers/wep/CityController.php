<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\City;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
    public function index(Request $request)
    {
        try {
            $flag = "show-cities";
            $query = City::query();
            $searchName = $request->input('search_name');
            if ($searchName) {
                $query->where("title", "like", "%{$searchName}%");
            }
            $cities = $query->get();
            return view("panel.dashboard.cities.cities", compact("flag", "cities", 'searchName'));
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
    public function create(Request $request)
    {
        try {
            $add = $request->add ? $request->add : Null;
            return view("panel.dashboard.cities.add", ["flag" => "add-city", 'add' => $add]);
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
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
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
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
        try {
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
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
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
                    "title.regex" => "الاسم الذي ادخلته يجب ان يحتوي على حروف  باللغة العربية فقط",
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
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    /**
     * Soft delete for a specified resource .
     */
    public function delete(Request $request)
    {
        try {

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
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
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
        try {
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
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }
    public function showEdit()
    {
        try {
            $flag = 'edit-city';
            $cities = City::all();
            return view("panel.dashboard.cities.edit", compact('flag', 'cities'));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }
}
