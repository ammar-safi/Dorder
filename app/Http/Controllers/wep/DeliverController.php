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
        // dd($request->all());
        try {


            $validate = Validator::make(
                $request->all(),
                [
                    "city_id" => "nullable|exists:cities,id",
                    "area_id" => "exists:areas,id",
                ],
                [
                    "city_id.exists" => "حدث حطأ , حاول مرا اخرى",
                    "area_id.exists" => "حدث حطأ , حاول مرا اخرى",
                ]
            );

            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate)->withInput($request->all());
            }



            $flag = "deliver-show";

            $cities = City::all();

            $show = $request->input('show') ? $request->input('show') : "delivers";
            $searchName = $request->input('search_name');
            $selectedCityId = $request->input("city_id");
            $selectedAreaId = $request->input("area_id");
            $areas = $selectedCityId ? Area::where('city_id', $selectedCityId)->get() : '';

            $query = User::query()->where('type', 'deliver');

            if ($show == "delivers") {
                if ($selectedAreaId) {
                    $query->whereIn(
                        "id",
                        Deliver::where('area_id', $selectedAreaId)->pluck('deliver_id')->toArray()
                    );
                } elseif ($selectedCityId) {
                    $query->WhereIn(
                        'id',
                        Deliver::whereIn(
                            "area_id",
                            Area::where("city_id", $selectedCityId)->pluck("id")->toArray()
                        )->pluck('deliver_id')->toArray()
                    );
                } else {
                    $query->whereIn('id', Deliver::pluck('deliver_id')->toArray());
                }
            } elseif ($show == "baned") {
                $query->onlyTrashed();
            } elseif ($show == "deleted") {
                $query->whereNotIn('id', Deliver::distinct()->pluck('deliver_id')->toArray());
            }

            if ($searchName) {
                $query->where("name", 'LIKE', "%{$searchName}%");
            }

            if ($selectedCityId || $selectedAreaId || $searchName || $show != "deliver") {
                $delivers = $query->get();
            } else {
                $delivers = null;
            }
            return view("panel.dashboard.delivers.delivers", compact("flag", "delivers", 'show', "cities", "areas", 'selectedCityId', 'selectedAreaId', 'searchName'));
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $flag = 'deliver-add';
        try {
            $collection = [];
            $areas = Area::distinct()->pluck("city_id");
            foreach ($areas as $cityId) {
                $city = City::find($cityId);
                if ($city) {
                    $collection[$city->title] = Area::where("city_id", $cityId)->get();
                }
            }
            return view("panel.dashboard.delivers.add", compact("flag", "collection"));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ غير معروف , الرجاء إعادة المحاولة");
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
                    // "active" => 'nullable|boolean',
                    "area_id" => 'nullable|exists:areas,id',
                ],
                [
                    'name.required' => 'الرجاء إدخال اسم عامل التوصيل.',
                    'name.regex' => 'الرجاء إدخال اسم عامل التوصيل باللغة العربية فقط.',
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
                    'mobile.regex' => 'رقم الهاتف غير صالح.',

                    'area_id.exists' =>  'المنطقة غير موجودة '
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput($request->all());
            }

            $deliver = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'mobile' => $request->mobile,
                'uuid' => \Str::uuid(),
                'type' => 'deliver',
                // 'active' => $request->active ? 1 : 0,
            ]);
            // dd($deliver);
            if ($deliver) {
                if ($request->area_id) {

                    if (Deliver::create([
                        'deliver_id' => $deliver->id,
                        'area_id' => $request->area_id,
                    ])) {
                        return redirect()->route("delivers.show")->with("success", "تمت الاضافة  بنجاح");
                    } else {
                        return redirect()->route("delivers.show")->with("error",  " حصل خطأاثناء تعيين المنطقة , قم بتعيين المنطقة بشكل يدوي من 'تعيين منطقة'");
                    }
                }
                return redirect()->route("delivers.show")->with("success", "تمت الاضافة  بنجاح");
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
                'id' => "required|exists:users,id",
                "city_id" => "nullable|exists:cities,id",
                "area_id" => "nullable|exists:areas,id",
            ],
            [
                'id.required' => "حصل خطأ غير متوقع",
                'id.exist' => "حصل خطأ غير متوقع",
                "city_id.exists" => "",
                "area_id.exists" => "",

            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->first())->withInput($request->all());
        }

        try {

            $deliver = User::where('type', 'deliver')->find($request->id);
            if (!$deliver) {
                return redirect()->back()->with("error", "حدث خطأ , يرجى المحاولة لاحقا");
            }
            $cities = city::all();
            $areas = Area::where("city_id", $request->input("city_id") ? $request->input("city_id") : ($deliver->deliver ? $deliver->deliver->area->city->id : null))->get();
            // dd($request->input("area_id"));
            $name = $request->input("name") ? $request->input("name") : $deliver->name;
            $email = $request->input("email") ? $request->input("email") : $deliver->email;
            $mobile = $request->input('mobile') ? $request->input('mobile') : $deliver->mobile;

            $selectedCityId = $request->input("city_id") ? $request->input("city_id") : ($deliver->deliver ? $deliver->deliver->area->city->id : null);
            $selectedAreaId = $request->input("area_id") ? $request->input("area_id") : ($deliver->deliver ? $deliver->deliver->area->id : null);

            return view("panel.dashboard.delivers.edit", compact('flag', 'deliver', 'cities', "areas", 'selectedCityId', 'selectedAreaId', 'name', 'email', 'mobile'));
        } catch (Exception $e) {
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,)
    {
        // dd($request->all());
        $validator = Validator::make(
            $request->all(),
            [
                'id' => ["required", Rule::exists('users', 'id')->where('type', "deliver")],
                'name' => 'required|string|regex:/^[\p{Arabic}\s]+$/u|max:255',
                'city_id' => 'nullable|exists:cities,id',
                'area_id' => [
                    'required_with:city_id',
                    'nullable',
                    Rule::exists('areas', 'id')->where(function (Builder $query) use ($request) {
                        return $query->where('city_id', $request->city_id);
                    }),

                ],
                "mobile" => [
                    "regex:/^09[0-9]{8}$/",
                    "string",
                    "max:20",
                    Rule::unique("users", "mobile")->where("type", "deliver")->ignore($request->mobile, 'mobile'),
                ],
                "email" => [
                    "email",
                    Rule::unique("users", "email")->where("type", "deliver")->ignore($request->email, 'email'),
                ],

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

                'city_id.required' => 'يرجى تحديد المدينة.',
                'city_id.required' => 'يرجى تحديد المدينة.',
                'city_id.exists' => 'المدينة المحددة غير موجودة.',

                'area_id.required_with' => 'يرجى تحديد المنطقة.',
                'area_id.exists' => 'يرجى تحديد منطقة.',
                // 'area_id.exists' => 'المنطقة المحددة غير موجودة ضمن المدينة المحددة.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        try {
            $deliver = User::where('type', 'deliver')->where('id', $request->id)->first();
            if (!$deliver) {
                return redirect()->back()->with('error', 'المشرف غير موجود.');
            }

            $data = [
                'name' => $request->name,
                "email" => $request->email,
                "mobile" => $request->mobile,
            ];
            if ($request->input("area_id")) {
                $deliver->deliver()->updateOrCreate(["deliver_id" => $request->id], ["area_id" => $request->area_id]);
            }
            if ($deliver->update($data)) {
                return redirect()->route("delivers.show")->with("success", 'تم التعديل بنجاح');
            } else {
                return redirect()->back()->with('error', 'لم يتم التعديل , حاول مرة اخرى');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
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
            $deliver = User::where('type', 'deliver')->where('id', $request->id)->first();
            $cities = City::all();
            $areas = $request->input("city_id") ? Area::where("city_id", $request->input("city_id"))->get() : "";
            $selectedCityId = $request->input("city_id") ? $request->input("city_id") : '';
            $selectedAreaId = $request->input("area_id") ? $request->input("area_id") : '';


            return view("panel.dashboard.delivers.employ", compact("areas", 'deliver', 'cities', 'selectedCityId', 'selectedAreaId'));
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
            [
                'area_id.required' => "يجب تحديد منطقة",
                'area_id.exists' => "حصل خطأ , الرجاء اعادة المحاولة",
            ]
        );
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate);
        }
        // dd($request->all());
        try {
            $monitor = User::where('type', 'deliver')->where('id', $request->id)->first();
            if ($monitor->deliver()->exists()) {
                return redirect()->back()->with("error", "هذا العامل غير متاح للتوظيف");
            }
            if ($monitor->deliver()->create(['area_id' => $request->area_id])) {
                return redirect()->route("delivers.show")->with("success", "تم تعيين عامل التوصيل بنجاح");
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
    public function delete(Request $request)
    {
        try {
            $validate = Validator::make(['id' => $request->id], ['id' => "required"], [
                'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة"
            ]);
            if ($validate->fails()) {
                return redirect()->back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
            }

            $is_exist = Deliver::where('deliver_id', $request->id)->first();
            if ($is_exist && $is_exist->forceDelete()) {
                return redirect()->back()->with("success", "تم اقالة المشرف بنجاح");
            }
            return redirect()->back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
    public function ban(Request $request)
    {
        $validate = Validator::make(['id' => $request->id], ['id' => "required"]);
        if ($validate->fails()) {
            return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
        }
        try {
            $deliver = User::where("type", "deliver")->where('id', $request->id)->first();

            if ($deliver->deliver()->exists()) {
                $deliver->deliver()->forceDelete();
            }

            if ($deliver->delete()) {
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
    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request)
    {
        try {
            $validate = Validator::make(['id' => $request->id], ['id' => "required"], [
                'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة"
            ]);
            if ($validate->fails()) {
                return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
            }

            $is_exist = User::where('type', 'deliver')->onlyTrashed()->find($request->id);
            if ($is_exist && $is_exist->restore()) {
                return redirect()->back()->with("success", "تم استرجاع المشرف بنجاح");
            }
            return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
        } catch (Exception $e) {
            // Log::error("حدث خطأ: " . $e->getMessage(), [
            //     'exception' => $e
            // ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }
}
