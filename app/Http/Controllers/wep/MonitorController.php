<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Monitor;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Psy\CodeCleaner\ReturnTypePass;

class MonitorController extends Controller
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
            $flag = "show-monitors";

            $cities = City::all();

            $query = Monitor::query();

            $searchName = $request->input('search_name');
            // dd($searchName);
            $selectedCityId = $request->input("city_id");
            $selectedAreaId = $request->input("area_id");
            $areas = $selectedCityId ? Area::where('city_id', $selectedCityId)->get() : '';

            if ($selectedAreaId) {
                $query->where("area_id", $selectedAreaId);
            } elseif ($selectedCityId) {
                $query->whereIn("area_id", (Area::where("city_id", $selectedCityId)->pluck("id")->toArray()));
            }

            if ($searchName) {
                $query->whereIn('monitor_id', User::where("type", "monitor")->where("name", 'LIKE', "%{$searchName}%")->pluck('id')->toArray());
            }

            if ($selectedCityId || $selectedAreaId || $searchName) {
                $monitors = $query->get();
            } else {
                $monitors = null;
            }
            // $monitors = $selectedAreaId?


            // dd($monitors);
            //     Monitor::where("area_id", $selectedAreaId)->get()

            //     :($selectedCityId ?
            //         Monitor::whereIn("area_id", 
            //             Area::where("city_id", $selectedCityId)->pluck("id")->toArray())
            //             ->get() : '');


            // dd($selectedAreaId);
            // dd($monitors);



            return view("panel.dashboard.monitors.monitors", compact("flag", "monitors", "cities", "areas", 'selectedCityId', 'selectedAreaId', 'searchName'));
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

            $collection = [];
            $flag = "add-monitor";


            $areas = Area::distinct()->pluck("city_id");

            foreach ($areas as $cityId) {
                $city = City::find($cityId);

                if ($city) {
                    $collection[$city->title] = Area::where("city_id", $cityId)->get();
                }
            }

            return view("panel.dashboard.monitors.add", compact("flag", "collection"));
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|regex:/^[\p{Arabic}\s]+$/u|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'mobile' => 'required|nullable|string|max:20|unique:users,mobile',
                // "active" => 'nullable|boolean',
                "area_id" => 'nullable|exists:areas,id',
            ], [
                'name.required' => 'الرجاء إدخال اسم المشرف.',
                'name.regex' => 'الرجاء إدخال اسم المشرف باللغة العربية فقط.',
                'name.string' => 'يجب أن يكون الاسم نصًا.',
                'name.max' => 'لا يمكن أن يتجاوز طول الاسم 255 حرفًا.',

                'email.required' => 'الرجاء إدخال البريد الإلكتروني.',
                'email.email' => 'الرجاء إدخال بريد إلكتروني صالح.',
                'email.unique' => 'البريد الإلكتروني مُستخدم بالفعل.',

                'password.required' => 'الرجاء إدخال كلمة المرور.',
                'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
                'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل.',
                'password.confirmed' => 'كلمتين السر غير متطابقتين',

                'mobile.required' => 'الرجاء إدخال رقم الهاتف.',
                'mobile.string' => 'يجب أن يكون رقم الهاتف نصًا.',
                'mobile.max' => 'لا يمكن أن يتجاوز طول رقم الهاتف 20 حرفًا.',
                "mobile.unique" => "هذا الرقم قيد الاستخدام بالفعل",

                'area_id.exists' =>  'المنطقة غير موجودة '
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput($request->all());
            }

            $monitor = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'mobile' => $request->mobile,
                'uuid' => Str::uuid(),
                'type' => 'monitor',
                // 'active' => $request->active ? 1 : 0,
            ]);
            // dd($monitor);
            if ($monitor) {
                if ($request->area_id) {

                    if (Monitor::create([
                        'monitor_id' => $monitor->id,
                        'area_id' => $request->area_id,
                    ])) {
                        return redirect()->route("monitors.show")->with("update_success", "تمت الاضافة  بنجاح");
                    } else {
                        return redirect()->route("monitors.show")->with("error",  " حصل خطأاثناء تعيين المنطقة , قم بتعيين المنطقة بشكل يدوي من 'تعيين منطقة'");
                    }
                }
                return redirect()->route("monitors.show")->with("update_success", "تمت الاضافة  بنجاح");
            } else {
                return back()->with("error",  "حصل خطأ غير متوقع , يرجى المحاولة لاحفا");
            }
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
            // dd(User::find(43));   
            $validate = Validator::make(['id' => $request->id], ['id' => "required|exists:monitors,id"], [
                'id.required' => "حصل خطأ غير متوقع",
                'id.exist' => "حصل خطأ غير متوقع"
            ]);

            if ($validate->fails()) {
                return back()->withInput($request->all())->withErrors($validate);
            }

            $monitor = Monitor::find($request->id);
            if ($monitor) {

            } else {
                return back()->with("error", "حدث خطأ , يرجى المحاولة لاحقا");
            }

            $flag = 'monitors-show';
            return view("panel.dashboard.monitors.edit" , compact('flag','monitor'));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حدث خطأ , يرجى المحاولة لاحقا");
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

    public function delete(Request $request)
    {
        try {
            $validate = Validator::make(['id' => $request->id], ['id' => "required"], [
                'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة"
            ]);
            if ($validate->fails()) {
                return back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
            }


            $is_exist = Monitor::find($request->id);
            if ($is_exist && $is_exist->delete()) {
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



    public function active(Request $request)
    {
        try {
            $validate = Validator::make(['id' => $request->id], ['id' => "required"], ['id.required' => "حصل خطأ غير متوقع"]);

            if ($validate->fails()) {
                return back()->withInput($request->all())->withErrors($validate);
            }

            $monitor = User::find($request->id);

            if ($monitor->type == "monitor" && $monitor->update(["active" => $monitor->active ? 0 : 1])) {
                return back();
            } else {
                return back()->with("error", "حدث خطأ غير معروف , اعد المحاولة لاحقا");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }


    public function editArea()
    {
        try {
            // $flag = "add-monitor";
            // $monitors = Monitor::
            // return view
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }
}
