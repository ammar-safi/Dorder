<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Image;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "hasAccess"]);
        $this->middleware('isAdmin')->except(["index", "delete"]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'id' => [
                    'nullable',
                    Rule::exists("users", 'id')->where("type", "client"),
                ],
                "city_id" => "nullable|exists:cities,id",
                'area_id' => [
                    'nullable',
                    Rule::exists('areas', 'id')->where('city_id', $request->city_id),

                ]
            ],
            [
                "id.exists" => "حدث حطأ , حاول مرا اخرى",
                "city_id.exists" => "حدث حطأ , حاول مرا اخرى",
                "area_id.exists" => "حدث حطأ , حاول مرا اخرى",
            ]
        );
        if ($validate->fails()) {
            return redirect()->back()->with("error", 'حدث خطأ اثناء البحث, حاول مرا اخرى')->withErrors($validate->errors()->first());
        }

        try {
            $flag = 'clients-show';
            $cities = City::all();
            $query = User::query()->where("type", 'client');
            $id = $request->input("id");
            $show = $request->input('show', 'show');
            $searchName = $request->input('search_name');
            $selectedCityId = $request->input("city_id");
            $selectedAreaId = $request->input("area_id");
            $areas = $selectedCityId ? Area::where('city_id', $selectedCityId)->get() : '';
            if ($show == "deleted") {
                $query->onlyTrashed();
            } elseif ($show == "active") {
                $query->where("expire", ">", Carbon::now());
            } elseif ($show == "not_active") {
                $query->whereNull("package_id")->orWhere("expire", '<', Carbon::now());
            }
            if ($selectedAreaId) {
                $query->where("area_id", $selectedAreaId);
            } elseif ($selectedCityId) {
                $query->whereIn("area_id", (Area::where("city_id", $selectedCityId)->pluck("id")->toArray()));
            }
            if ($searchName) {
                $query->where("name", "like", "%" . $searchName . "%");
            }
            if ($request->has('id')) {
                $query->withTrashed()->find($request->id);
            }
            if ($selectedAreaId || $selectedCityId || $searchName || $show != "show" || $request->hasAny('id')) {
                $clients = $query->get();
            } else {
                $clients = null;
            }
            return view('panel.dashboard.clients.clients', compact('clients', 'cities', 'areas', 'flag', 'selectedCityId', 'selectedAreaId', 'searchName', "show", 'id'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $collection = [];

            $areas = Area::distinct()->pluck("city_id");

            foreach ($areas as $cityId) {
                $city = City::find($cityId);

                if ($city) {
                    $collection[$city->title] = Area::where("city_id", $cityId)->get();
                }
            }
            // dd($client);
            $flag = "clients-show";
            return view('panel.dashboard.clients.add', compact('flag', 'collection'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $validate = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'email' => ['required', 'email', 'max:255', Rule::unique("users", "email")->where('type', "client")],
                    'mobile' => ['required', 'string', 'regex:/^09[0-9]{8}$/',  Rule::unique("users", "mobile")->where('type', "client")],
                    'subscription_fees' => 'nullable|required_with:expire|numeric',
                    'password' => 'required|string|min:8|confirmed',
                    'expire' => 'nullable|date_format:Y-m-d',
                    'area_id' => 'nullable|exists:areas,id',
                    'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ],
                [
                    'name.required' => "اسم العميل مطلوب",

                    'mobile.required' => 'رقم الهاتف مطلوب',
                    'mobile.unique' => 'رقم الهاتف موجود مسبقا',
                    'mobile.regex' => 'الرقم غير صحيح',

                    'email.required' => 'البريد الالكتروني مطلوب',
                    'email.email' => 'يرجى ادخال بريد الكتروني صالح',
                    'email.unique' => 'البريد الالكتروني موجود مسبقا',

                    'area_id.exists' => 'المنطقة غير صحيحة , حاول مرة اخرة',

                    'subscription_fees.numeric' => 'عدد الطلبات يجب ان يكون رقم ',
                    'subscription_fees.required_with' => "يجب اختيار عدد الطلبات",

                    'password.required' => 'الرجاء إدخال كلمة المرور.',
                    'password.string' => 'يجب أن تكون كلمة المرور نصًا.',
                    'password.min' => 'يجب أن تكون كلمة المرور 8 أحرف على الأقل.',
                    'password.confirmed' => 'كلمتين السر غير متطابقتين',
    
                    'expire.date_format' => 'تاريخ انتهاء الصلاحية يجب ان يكون من الشكل : YYYY-MM-DD',

                    'profile_image.image' => 'يجب ان يكون صورة',
                    'profile_image.mimes' => 'صيغة الصورة غير صالحة',
                    'profile_image.max' => 'حجم الصورة كبير',
                ]
            );
            if ($validate->fails()) {
                return redirect()->back()->withInput($request->all())->withErrors($validate);
            }
            $client = User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => $request->password ,
                'type' => 'client',
                'area_id' => $request->area_id,
                'active' => Carbon::parse($request->expire)->isFuture(),
                'subscription_fees' =>  $request->subscription_fees,
                'expire' => $request->expire,
            ]);

            if ($client) {
                if ($request->file('profile_image')) {
                    // if ($client->image && Storage::exists('public/' . $client->image->url)) {
                    //     Storage::delete('public/' . $client->image->url);
                    //     $client->image->delete();
                    // }
                    $file = $request->file('profile_image');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('clients', $filename, 'public');

                    $image = new Image();
                    $image->url = $path;
                    if ($client->image()->save($image)) {
                        return redirect()->route("clients.show")->with("success", 'تم اضافة العميل بنجاح');
                    } else {
                        return redirect()->back()->with("error", 'تمت اضافة العميل لكن حدث خطأ اثناء اضافة الصورة');
                    }
                }
                return redirect()->route("clients.show")->with("success", 'تم تعديل العميل بنجاح');
            } else {
                return redirect()->back()->with("error", 'حدث خطأ, حاول مرا اخرى');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request)
    {
        // dd($request->all());
        try {
            $validate = Validator::make(
                ['id' => $request->id],
                [
                    'id' => 'required|exists:users,id',
                ],
                [
                    'id.required' => 'حدث خطأ , حاول مرا اخرى',
                    'id.exists' => 'حدث خط , حاول مرا اخرى',
                ]
            );

            if ($validate->fails()) {
                return redirect()->back()->with("error", 'حدث خطأ , حاول مرا اخرى')->withErrors($validate->errors());;
            }

            $client = User::find($request->id);
            if ($client && $client->type == "client") {


                $collection = [];

                $areas = Area::distinct()->pluck("city_id");

                foreach ($areas as $cityId) {
                    $city = City::find($cityId);

                    if ($city) {
                        $collection[$city->title] = Area::where("city_id", $cityId)->get();
                    }
                }
                // dd($client);
                $flag = "clients-show";
                return view('panel.dashboard.clients.edit', compact('flag', 'client', 'collection'));
            } else {
                return redirect()->back()->with("error", 'حدث خطأ, حاول مرا اخرى');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request)
    {
        try {

            $validate = Validator::make(
                $request->all(),
                [
                    'id' => 'required|exists:users',
                    'name' => 'required|string|max:255',
                    'email' => ['required', 'email', 'max:255', Rule::unique("users", "email")->where('type', "client")->ignore($request->id, "id")],
                    'mobile' => ['required', 'string', 'regex:/^09[0-9]{8}$/',  Rule::unique("users", "mobile")->where('type', "client")->ignore($request->id, "id")],
                    'subscription_fees' => 'nullable|required_with:expire|numeric',
                    'expire' => 'nullable|date_format:Y-m-d',
                    'area_id' => 'nullable|exists:areas,id',
                    'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                ],
                [
                    'id.required' => 'حدث خطأ , حاول مرا اخرى',
                    'id.exists' => 'حدث خطأ , حاول مرا اخرى',

                    'name.required' => "اسم العميل مطلوب",

                    'mobile.required' => 'قم الهاتف مطلوب',
                    'mobile.unique' => 'رقم الهاتف موجود مسبقا',
                    'mobile.regex' => 'الرقم غير صحيح',

                    'email.required' => 'البريد الالكتروني مطلوب',
                    'email.email' => 'يرجى ادخال بريد الكتروني صالح',
                    'email.unique' => 'البريد الالكتروني موجود مسبقا',

                    'area_id.exists' => 'المنطقة غير صحيحة , حاول مرة اخرة',

                    'subscription_fees.numeric' => 'عدد الطلبات يجب ان يكون رقم ',
                    'subscription_fees.required_with' => "يجب اختيار عدد الطلبات",

                    'expire.date_format' => 'تاريخ انتهاء الصلاحية يجب ان يكون من الشكل : YYYY-MM-DD',

                    'profile_image.image' => 'يجب ان يكون صورة',
                    'profile_image.mimes' => 'صيغة الصورة غير صالحة',
                    'profile_image.max' => 'حجم الصورة كبير',
                ]
            );



            if ($validate->fails()) {
                return redirect()->back()->withInput($request->all())->withErrors($validate);
            }
            $client = User::find($request->id);
            if ($client && $client->type == "client") {
                $client->name = $request->name;
                $client->mobile = $request->mobile;
                $client->email = $request->email;
                $client->area_id = $request->area_id;
                $client->active = Carbon::parse($request->expire)->isFuture();
                $client->subscription_fees = $request->subscription_fees;
                $client->expire = $request->expire;
                if ($client->save()) {
                    if ($request->file('profile_image')) {
                        if ($client->image && Storage::exists('public/' . $client->image->url)) {
                            Storage::delete('public/' . $client->image->url);
                            $client->image->delete();
                        }
                        $file = $request->file('profile_image');
                        $filename = time() . '_' . $file->getClientOriginalName();
                        $path = $file->storeAs('clients', $filename, 'public');

                        $image = new Image();
                        $image->url = $path;
                        if ($client->image()->save($image)) {
                            return redirect()->route("clients.show")->with("success", 'تم تعديل العميل بنجاح');
                        } else {
                            return redirect()->back()->with("error", 'حدث خطأ اثناء  تعديل الصورة , حاول مرا اخرى');
                        }
                    }
                    return redirect()->route("clients.show")->with("success", 'تم تعديل العميل بنجاح');
                } else {
                    return redirect()->back()->with("error", 'حدث خطأ, حاول مرا اخرى');
                }
            } else {
                return redirect()->back()->with("error", 'حدث خطأ, حاول مرا اخرى');
            }
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
                return redirect()->back()->with('success', 'تم حظر العميل بنجاح');
            }

            return redirect()->back()->with('error', 'حدث خطأ اثناء الحذف, حاول مرا اخرى');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function restore(Request $request)
    {
        $validate = Validator::make($request->all(), ['id' => 'required|exists:users']);
        if ($validate->fails()) {
            return redirect()->back()->with("error", 'حدث خطأ, حاول مرا اخرى')->withErrors($validate->errors());;
        }
        try {
            $client = User::Where("type", 'client')->where("id", $request->id)->onlyTrashed()->first();
            if ($client->restore()) {
                return redirect()->back()->with('success', 'تم استرجاع العميل بنجاح');
            }

            return redirect()->back()->with('error', 'حدث خطأ اثناء الاسترجاع, حاول مرا اخرى');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
