<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Monitor;
use App\Models\User;
use Exception;
use Google\Service\Dfareporting\Resource\Cities;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        // dd($request->all());
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    "city_id" => "nullable|exists:cities,id",
                    "area_id" => "nullable|exists:areas,id",
                    'show' => "in:monitors,deleted,baned"
                ],
                [
                    "city_id.exists" => "حدث حطأ , حاول مرا اخرى",
                    "area_id.exists" => "حدث حطأ , حاول مرا اخرى",
                ]
            );

            if ($validate->fails()) {
                // dd('');
                // dd($request->input("search_name"));
                return redirect()->back()->withErrors($validate)->withInput($request->all());
            }

            $flag = "show-monitors";

            $cities = City::all();
            $show = $request->input('show') ? $request->input('show') : "monitors";
            $searchName = $request->input('search_name');
            $selectedCityId = $request->input("city_id");
            $selectedAreaId = $request->input("area_id");
            $areas = $selectedCityId ? Area::where('city_id', $selectedCityId)->get() : '';
            if ($show == "monitors") {

                $query = Monitor::query();
                if ($selectedAreaId) {
                    $query->where("area_id", $selectedAreaId);
                } elseif ($selectedCityId) {
                    $query->whereIn("area_id", (Area::where("city_id", $selectedCityId)->pluck("id")->toArray()));
                }

                $query_2 = clone $query;
                if ($searchName) {
                    $query_2->whereIn('monitor_id', User::where("type", "monitor")->where("name", 'LIKE', "%{$searchName}%")->pluck('id')->toArray());
                }

                if ($selectedCityId || $selectedAreaId || $searchName) {
                    $monitors = $query_2->get();
                } else {
                    $monitors = null;
                }
            } else {
                $query = User::query();

                if ($searchName) {
                    $query->where("name", 'LIKE', "%{$searchName}%");
                }
                if ($show == 'baned') {
                    $query->onlyTrashed()->where("type", "monitor");
                } elseif ($show == "deleted") {
                    $query->whereNotIn('id', Monitor::distinct()->pluck('monitor_id')->toArray())->where("type", "monitor");
                }
                $monitors = $query->get();
            }

            return view("panel.dashboard.monitors.monitors", compact("flag", 'show', "monitors", "cities", "areas", 'selectedCityId', 'selectedAreaId', 'searchName'));
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
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|regex:/^[\p{Arabic}\s]+$/u|max:255',
                    'email' => 'required|email|unique:users,email',
                    'password' => 'required|string|min:8|confirmed',
                    'mobile' => 'required|nullable|regex:/^09[0-9]{8}$/|string|max:20|unique:users,mobile',
                    "area_id" => 'nullable|exists:areas,id',
                ],
                [
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
                    "mobile.regex" => 'يجب ادخال رقم هاتف صحيح',

                    'area_id.exists' =>  'المنطقة غير موجودة '
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
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
                return redirect()->route("monitors.show")->with("success", "تمت الاضافة  بنجاح");
            } else {
                return redirect()->back()->with("error",  "حصل خطأ غير متوقع , يرجى المحاولة لاحفا");
            }
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
            $validate = Validator::make(
                $request->all(),
                [
                    'id' => "required|exists:users,id",
                    // "city_id" => "nullable|exists:cities,id",
                    // "area_id" => "exists:areas,id",
                    // "name" => "nullable",
                    // "email" => "nullable",
                    // "mobile" => "nullable",
                ],
                [
                    'id.required' => "حصل خطأ غير متوقع",
                    'id.exist' => "حصل خطأ غير متوقع",
                    "city_id.exists" => "",
                    "area_id.exists" => "",

                ]
            );
            if ($validate->fails()) {
                // dd($request->all());
                return redirect()->back()->withInput($request->all())->withErrors($validate);
            }

            $monitor = User::find($request->id);

            if (!$monitor) {
                return redirect()->back()->with("error", "حدث خطأ , يرجى المحاولة لاحقا");
            }
            $cities = city::all();
            $areas = Area::whereIn("id", $monitor->monitors ? $monitor->monitors()->pluck('area_id')->toArray() : null)->get();

            // dd($areas);
            $name = $request->input("name") ? $request->input("name") : $monitor->name;
            $email = $request->input("email") ? $request->input("email") : $monitor->email;
            $mobile = $request->input('mobile') ? $request->input('mobile') : $monitor->mobile;
            $selectedCityId = $request->input("city_id") ? $request->input("city_id") : null;
            $selectedAreaId = $request->input("area_id") ? $request->input("area_id") : null;
            $flag = 'show-monitors';
            return view("panel.dashboard.monitors.edit", compact('flag', 'monitor', 'cities', "areas", 'selectedCityId', 'selectedAreaId', 'name', 'email', 'mobile'));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'id' => "required|exists:users,id",
                "email" => [
                    "email",
                    Rule::unique("users", "email")->where("type", "monitor")->ignore($request->email, 'email'),
                ],
                "mobile" => [
                    "regex:/^09[0-9]{8}$/",
                    "string",
                    "max:20",
                    Rule::unique("users", "mobile")->where("type", "monitor")->ignore($request->mobile, 'mobile'),
                ],
                'name' => 'required|string|regex:/^[\p{Arabic}\s]+$/u|max:255',
            ],
            [
                'id.required' => "حصل خطأ غير متوقع",
                'id.exists' => "حصل خطأ غير متوقع",

                'email.email' => ' البريد الإلكتروني غير صالح',
                'email.unique' => 'البريد الإلكتروني مُستخدم بالفعل.',

                'mobile.regex' => 'رقم الهاتف غير صالح.',
                'mobile.string' => 'يجب أن يكون رقم الهاتف نص.',
                'mobile.max' => 'لا يمكن أن يتجاوز طول رقم الهاتف 20 رقم.',
                'mobile.unique' => 'رقم الهاتف مُستخدم بالفعل.',

                'name.required' => 'يرجى إدخال الاسم.',
                'name.string' => 'يجب أن يكون الاسم نصيًا.',
                'name.regex' => 'يجب أن يحتوي الاسم على أحرف عربية فقط.',
                'name.max' => 'يجب أن لا يتجاوز الاسم 255 حرفًا.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        try {
            $monitor = User::where("type", "monitor")->where('id', $request->id)->first();

            if (!$monitor) {
                return redirect()->back()->with('error', 'المشرف غير موجود.');
            }
            $data = [
                'name' => $request->name,
                "email" => $request->email,
                "mobile" => $request->mobile,
            ];
            // dd($request->area_id);
            if ($request->input("area_id")) {
                $deleted_areas = json_decode($request->area_id);
                foreach ($deleted_areas as $key=>$area_id){
                    $monitor->monitors()->where('area_id' , $area_id)->forceDelete();
                }
            }
            if ($monitor->update($data)) {
                return redirect()->route("monitors.show")->with("success", 'تم التعديل بنجاح');
            } else {
                return redirect()->back()->with('error', 'لم يتم التعديل , حاول مرة اخرى');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
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
                return redirect()->back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
            }


            $is_exist = Monitor::find($request->id);
            if ($is_exist && $is_exist->forceDelete()) {
                return redirect()->back()->with("success", "تم اقالة المشرف بنجاح");
            }
            return redirect()->back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    public function ban(Request $request)
    {
        $validate = Validator::make(['id' => $request->id], ['id' => "required"], [
            'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة"
        ]);
        if ($validate->fails()) {
            return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
        }
        try {
            $monitor = User::where("type", "monitor")->where('id', $request->id)->first();

            if ($monitor->Monitors()->exists()) {
                foreach ($monitor->monitors as $monitors) {
                    $monitors->forceDelete();
                }
            }

            if ($monitor->delete()) {
                return redirect()->back()->with("success", "تم حظر المشرف بنجاح");
            } else {
                return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $validateor = Validator::make([$request->id], ['id' => 'requried'], ['id.requried' => 'حصل خطا غير معروف , حاول مرة اخرى']);
        if ($validateor->fails()) {
            return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
        }
        try {
            $monitor = User::onlyTrashed()->where("type", "monitor")->where('id', $request->id)->first();
            if ($monitor->restore()) {
                return redirect()->back()->with("success", "تم استرجاع المشرف بنجاح");
            } else {
                return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }


    public function active(Request $request)
    {
        try {
            $validate = Validator::make(['id' => $request->id], ['id' => "required"], ['id.required' => "حصل خطأ غير متوقع"]);

            if ($validate->fails()) {
                return redirect()->back()->withInput($request->all())->withErrors($validate);
            }

            $monitor = User::find($request->id);

            if ($monitor->type == "monitor" && $monitor->update(["active" => $monitor->active ? 0 : 1])) {
                return redirect()->back();
            } else {
                return redirect()->back()->with("error", "حدث خطأ غير معروف , اعد المحاولة لاحقا");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }

    public function Employ(Request $request)
    {
        // dd($request->all());
        $validate = Validator::make(
            $request->all(),
            [
                'id' => "required",
                "city_id" => "nullable|exists:cities,id",
                "area_id" => "nullable|exists:areas,id",
            ],
        );
        if ($validate->fails()) {
            return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
        }
        try {
            $monitor = User::where('type', 'monitor')->where('id', $request->id)->first();
            $cities = City::all();
            $areas = $request->input("city_id") ? Area::where("city_id", $request->input("city_id"))->get() : "";
            $selectedCityId = $request->input("city_id") ? $request->input("city_id") : '';
            $selectedAreaId = $request->input("area_id") ? $request->input("area_id") : '';


            return view("panel.dashboard.monitors.employ", compact("areas", 'monitor', 'cities', 'selectedCityId', 'selectedAreaId'));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }


    public function SetEmploy(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id' => "required",
                "area_id" => "required|exists:areas,id",
            ],
        );
        if ($validate->fails()) {
            return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
        }
        // dd($request->all());
        try {
            $monitor = User::where('type', 'monitor')->where('id', $request->id)->first();
            if ($monitor->Monitors()->create(['area_id' => $request->area_id])) {
                return redirect()->route("monitors.show")->with("success", "تم تعيين المشرف بنجاح");
            } else {
                return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
