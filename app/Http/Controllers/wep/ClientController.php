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
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    "city_id" => "nullable|exists:cities,id",
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
                return redirect()->back()->with("error", 'حدث خطأ اثناء البحث, حاول مرا اخرى')->withErrors($validate->errors());;
            }


            $flag = 'clients-show';
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
                $client->active = Carbon::parse($request->expire)->isFuture() ? true : false;
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
                        $path = $file->storeAs('clients', $filename , 'public');

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
}
