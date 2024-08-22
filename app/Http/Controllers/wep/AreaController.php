<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Deliver;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AreaController extends Controller
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
        try {
            $flag = "show-areas";
            $collection = [];

            $areas = Area::distinct()->pluck("city_id");

            foreach ($areas as $cityId) {
                $city = City::find($cityId);

                if ($city) {
                    $collection[$city->title] = Area::where("city_id", $cityId)->get();
                }
            }

            // dd($collection);

            return view("panel.dashboard.areas.areas", compact("flag", "collection"));
        } catch (Exception $e) {
            Log::error("هنالك مشكلة , حاول مرة اخرى: " . $e->getMessage());
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $flag = "add-area";
            $cities = City::all();
            return view("panel.dashboard.areas.add", compact("flag", "cities"));
        } catch (Exception $e) {
            Log::error("هنالك مشكلة , حاول مرة اخرى: " . $e->getMessage());
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // dd($request->title);
            $data = [
                'title' => $request->title,
                'city_id' => $request->city_id,
            ];

            $rules = [
                'title' => ['required', 'string', 'regex:/^[\p{Arabic}\s]+$/u', Rule::unique('areas')->where("city_id", $data['city_id'])],
                'city_id' => 'required|exists:cities,id',
            ];

            $message = [
                'title.required' => "عليك ان تدخل اسم المنطقة ",
                'title.unique' => " المنطقة موجودة بالفعل ",
                'title.regex' => "اسم المنطقة يجب ان لا يحتوي الا على حروف عربية",

                'city_id.required' => "يجب ان تختار مدينة",
                'city_id.exists' => "حصل خطأ غير متوقع",

            ];

            $validate = Validator::make($data, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput($request->all())->withErrors($validate);
            }

            if (Area::create([
                "title" => $request->title,
                "city_id" => $request->city_id,
            ])) {
                return redirect()->route("areas.show")->with("update_success", "تمت الاضافة  بنجاح");
            } else {
                return back()->with("error",  "حصل خطأ غير متوقع , تعد المحاولة لاحفا");
            }
        } catch (Exception $e) {
            Log::error("هنالك مشكلة , حاول مرة اخرى: " . $e->getMessage());
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        try {
            // dd($request->id);
            $validate = Validator::make(["id" => $request->id], ['id' => "required"], ['id.required' => "حصل خطأ غير متوقع"]);

            if ($validate->fails()) {
                // dd("ammar");/
                return back()->withInput($request->all())->withErrors($validate);
            }

            $area = Area::find($request->id);

            if (!$area) {
                return back()->with("error", "حصل خطأ غير متوقع , حاول مرة اخرى ");
            }
            $cities = City::all();
            $flag = "show-areas";
            return view("panel.dashboard.areas.edit", compact("cities", 'flag', "area"));
        } catch (Exception $e) {
            Log::error("هنالك مشكلة , حاول مرة اخرى: " . $e->getMessage());
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $data = [
                'id' => $request->id,
                'title' => $request->title,
                'city_id' => $request->city_id,
            ];

            $rules = [
                'id' => "required",
                'title' => ['required', 'string', 'regex:/^[\p{Arabic}\s]+$/u', Rule::unique('areas')->where("city_id", $data['city_id'])->ignore($data["id"])],
                'city_id' => 'required|exists:cities,id',
            ];

            $message = [
                'id.required' => "حصل خطأ غير متوقع",

                'title.required' => "عليك ان تدخل اسم المنطقة ",
                'title.regex' => "اسم المنطقة يجب ان لا يحتوي الا على حروف عربية",
                'title.unique' => "لا يمكنك تعديل اسم المنطقة الى منطقة موجودة بالفعل",

                'city_id.required' => "يجب ان تختار مدينة",
                'city_id.exists' => "حصل خطأ غير متوقع",

            ];

            $validate = Validator::make($data, $rules, $message);

            if ($validate->fails()) {
                return back()->withInput($request->all())->withErrors($validate);
            }

            if (Area::find($request->id)->update([
                "title" => $request->title,
                "city_id" => $request->city_id,
            ])) {
                return redirect()->route("areas.show")->with("update_success", "تم التعديل بنجاح");
            } else {
                return back()->with("error",  "حصل خطأ غير متوقع , تعد المحاولة لاحفا");
            }
        } catch (Exception $e) {
            Log::error("هنالك مشكلة , حاول مرة اخرى: " . $e->getMessage());
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

    /**
     * Soft Delete for a specified resource from storage.
     */
    public function delete(Request $request)
    {

        try {
            $validate = Validator::make($request->all(), ['id' => "required"], [
                'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة"
            ]);
            if ($validate->fails()) {
                return back()->withErrors($validate);
            }

            $area = Area::find($request->id);
            if ($area) {
                $area->Monitors()->delete();
                $area->Delivers()->delete();
                $area->Users()->delete();
                $area->delete();
                return back();
            }

            return back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
        } catch (Exception $e) {
            Log::error("هنالك مشكلة , حاول مرة اخرى: " . $e->getMessage());
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }


    public function createEmploys(Request $request)
    {

        $validate = Validator::make($request->all(), ['id' => "required|exists:areas,id"], [
            'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة",
            'id.exist' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة",
        ]);
        if ($validate->fails()) {
            return back()->withErrors($validate);
        }

        $area = Area::find($request->id);
        $monitors = User::where('type', 'monitor')->get();
        $delivers = User::where('type', 'deliver')->get();
        $flag = "show-areas";
        if ($area) {
            return view("panel.dashboard.areas.addEmploys", compact("flag", "area", "monitors", "delivers"));
        } else {
            return back()->with("error", "حصل خطأ غير معروف , الرجاء اعادة المحاولة");
        }
    }


    public function storeEmploys(Request $request)
    {
        try {
            // التحقق من صحة البيانات الواردة
            $validate = Validator::make($request->all(), [
                'id' => 'required|exists:areas,id',
                'monitors' => 'array',
                'monitors.*' => 'exists:users,id',
                'delivers' => 'array',
                'delivers.*' => 'exists:users,id'
            ], [
                'id.required' => 'حصل خطأ غير معروف, الرجاء إعادة المحاولة',
                'id.exists' => 'المنطقة المحددة غير موجودة',
                'monitors.array' => 'بيانات المشرفين غير صحيحة',
                'monitors.*.exists' => 'مشرف غير موجود',
                'delivers.array' => 'بيانات عمال التوصيل غير صحيحة',
                'delivers.*.exists' => 'عامل توصيل غير موجود',
            ]);

            if ($validate->fails()) {
                return back()->withErrors($validate)->withInput();
            }

            $area = Area::find($request->id);

            if ($area) {

                $monitors = $request->input('monitors', []);
                $delivers = $request->input('delivers', []);
                // dd($monitors);

                $area->AreaMonitors()->sync($monitors); 
                $area->AreaDelivers()->sync($delivers);  

                return redirect()->route("areas.show")->with('success', 'تم التعيين بنجاح');
            } else {
                return back()->with('error', 'حصل خطأ غير معروف, الرجاء إعادة المحاولة');
            }
        } catch (Exception $e) {
            Log::error("هنالك مشكلة , حاول مرة اخرى: " . $e->getMessage());
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }
}
