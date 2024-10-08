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
        $this->middleware("isAdmin")->except("index");
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // dd($request->deleted);
            $flag = "show-cities";
            $deleted = $request->input("deleted");
            $query = City::query();
            $searchName = $request->input('search_name');
            if ($searchName) {
                $query->where("title", "like", "%{$searchName}%");
            }
            if ($deleted  == 'deleted') {
                $cities = $query->onlyTrashed()->get();
            } else {
                $cities = $query->get();
            }
            return view("panel.dashboard.cities.cities", compact("flag", "cities", 'searchName', "deleted"));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
                return redirect()->back()->withInput($request->all())->withErrors($validate);
            }

            $city = City::find($request->id);
            if ($city && $city->update(["title" => $request->title])) {
                return redirect()->route($request->route)->with("success", 'تم التعديل بنجاح');
            } else {
                session()->flash('error', "حصل خطأ غير متوقع");
                return redirect()->back();
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
                return redirect()->back()->withErrors($validate);
            }


            $city = City::find($request->id);
            if ($city) {

                foreach ($city->areas as $area) {
                    // dd($area);
                    if ($area->Monitors()->exists()) {
                        $area->Monitors()->delete();
                    }
                    if ($area->Delivers()->exists()) {
                        $area->Delivers()->delete();
                    }
                    if ($area->Users()->exists()) {
                        $area->Users()->delete();
                    }
                }
                $city->areas()->delete();
                $city->delete();
                return redirect()->back()->with("success", "تمت عمليه الحذف بنجاح");
            }
            return redirect()->back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function restore()
    {
        request()->validate([
            'id' => 'required|exists:cities,id'
        ], [
            'id.required' => 'حصل خطأ غير معروف، الرجاء إعادة المحاولة',
            'id.exists' => 'المدينة غير موجودة'
        ]);

        try {
            $city = City::onlyTrashed()->find(request()->id);

            if ($city) {

                foreach ($city->TrashedAreas() as $area) {
                    if ($area->Monitors()->onlyTrashed()->exists()) {
                        $area->Monitors()->restore();
                    }
                    // dd($area->Monitors()->onlyTrashed());
                    if ($area->Delivers()->onlyTrashed()->exists()) {
                        $area->Delivers()->restore();
                    }
                    // dd($area->Monitors);
                    if ($area->Users()->where("type", "client")->onlyTrashed()->exists()) {
                        $area->Users()->where("type", "client")->restore();
                    }
                }

                $city->areas()->restore();
                $city->restore();

                return redirect()->back()->with('success', 'تمت عملية الاسترجاع بنجاح');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حصل خطأ أثناء الاسترجاع: ' . $e->getMessage());
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
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }
}
