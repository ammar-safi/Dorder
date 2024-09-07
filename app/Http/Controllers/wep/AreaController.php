<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\Deliver;
use App\Models\User;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AreaController extends Controller
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
            $flag = "show-areas";

            $cities = City::all();

            $query = Area::query();
            // $cityQuery = City::query();             
            $collection = [];
            $searchName = $request->input('search_name');
            $selectedCityId = $request->input("city_id");

            if ($selectedCityId) {
                $query->where("city_id", $selectedCityId);
            }

            if ($searchName) {
                $query->where("title", "LIKE", "%{$searchName}%");
            }

            if ($selectedCityId || $searchName) {


                $areas = $query->distinct()->pluck("city_id");

                foreach ($areas as $cityId) {
                    $city = City::find($cityId);

                    if ($city) {
                        $query_2 = clone $query;
                        $collection[$city->title] = $query_2->where('city_id', $city->id)->get();
                    }
                }
            }

            // dd($collection);

            return view("panel.dashboard.areas.areas", compact("flag", "collection", "cities", "searchName", "selectedCityId"));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
                return redirect()->back()()->withInput($request->all())->withErrors($validate);
            }

            if (Area::create([
                "title" => $request->title,
                "city_id" => $request->city_id,
            ])) {
                return redirect()->route("areas.show")->with("update_success", "تمت الاضافة  بنجاح");
            } else {
                return redirect()->back()()->with("error",  "حصل خطأ غير متوقع , تعد المحاولة لاحفا");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
                return redirect()->back()()->withInput($request->all())->withErrors($validate);
            }

            $area = Area::find($request->id);

            if (!$area) {
                return redirect()->back()()->with("error", "حصل خطأ غير متوقع , حاول مرة اخرى ");
            }
            $cities = City::all();
            $flag = "show-areas";
            return view("panel.dashboard.areas.edit", compact("cities", 'flag', "area"));
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
                return redirect()->back()()->withInput($request->all())->withErrors($validate);
            }

            if (Area::find($request->id)->update([
                "title" => $request->title,
                "city_id" => $request->city_id,
            ])) {
                return redirect()->route("areas.show")->with("update_success", "تم التعديل بنجاح");
            } else {
                return redirect()->back()()->with("error",  "حصل خطأ غير متوقع , تعد المحاولة لاحفا");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
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
                return redirect()->back()()->withErrors($validate);
            }

            $area = Area::find($request->id);
            if ($area) {
                $area->Monitors()->delete();
                $area->Delivers()->delete();
                $area->Users()->delete();
                $area->delete();
                return redirect()->back()();
            }

            return redirect()->back()()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }


    public function createEmploys(Request $request)
    {

        $validate = Validator::make($request->all(), ['id' => "required|exists:areas,id"], [
            'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة",
            'id.exist' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة",
        ]);
        if ($validate->fails()) {
            return redirect()->back()()->withErrors($validate);
        }

        $area = Area::find($request->id);
        // dd($area);
        if (!$area) {
            return redirect()->back()()->with("error", "حدث خطأ , حاول مرة اخرى");
        }


        $monitors = User::leftJoin('monitors', 'users.id', '=', 'monitors.monitor_id')
            ->leftJoin('monitors as deleted_monitors', 'users.id', '=', 'deleted_monitors.monitor_id')
            ->where('users.type', 'monitor')->where(function ($query) {
                $query->whereNull('monitors.monitor_id')->orWhere(function ($query) {
                    $query->whereNotNull('deleted_monitors.monitor_id')
                        ->whereNotNull('deleted_monitors.deleted_at');
                });
            })
            ->distinct()
            ->select('users.*')
            ->get();

        $delivers = User::leftJoin('delivers', 'users.id', '=', 'delivers.deliver_id')
            ->leftJoin('delivers as deleted_delivers', 'users.id', '=', 'deleted_delivers.deliver_id')->where('users.type', 'deliver')->where(function ($query) {
                $query->whereNull('delivers.deliver_id')
                    ->orWhere(function ($query) {
                        $query->whereNotNull('deleted_delivers.deliver_id')
                            ->whereNotNull('deleted_delivers.deleted_at');
                    });
            })
            ->distinct()
            ->select('users.*')
            ->get();



        $flag = "show-areas";
        return view("panel.dashboard.areas.addEmploys", compact("flag", "area", "monitors", "delivers"));
    }


    public function storeEmploys(Request $request)
    {
        try {
            if ($request->input('monitors') || $request->input("delivers")) :

                // التحقق من صحة البيانات الواردة
                // dd($request->monitors);
                $validate = Validator::make($request->all(), [
                    'id' => 'required|exists:areas,id',
                    'monitors' => 'array',
                    'monitors.*' => [
                        Rule::exists('users', 'id')->where(function (Builder $query) {
                            return $query->where('type', 'monitor')->whereNull('deleted_at');
                        }),
                        Rule::unique('monitors', 'monitor_id')->where(function (Builder $query) use ($request) {
                            return $query->where('area_id', $request->id)->whereNull('deleted_at');
                        }),
                    ],


                    'delivers' => 'array',
                    'delivers.*' => [
                        Rule::exists('users', 'id')->where(function (Builder $query) {
                            return $query->where('type', 'deliver');
                        }),

                        Rule::unique('delivers', 'deliver_id')
                    ],


                ], [
                    'id.required' => 'حصل خطأ غير معروف, الرجاء إعادة المحاولة',
                    'id.exists' => 'المنطقة المحددة غير موجودة',
                    'monitors.array' => 'بيانات المشرفين غير صحيحة',
                    'monitors.*.exists' => "هنالك خطأ , هذا المشرف غير موجود",
                    'monitors.*.unique' => 'المشرف يعمل في هذه المنطقة بالفعل',
                    'delivers.array' => 'بيانات عمال التوصيل غير صحيحة',
                    'delivers.*.unique' => 'عامل التوصيل هذا غير متاح ',
                    'delivers.*.exists' => '  هنالك خطأ , عامل توصيل غير موجود',
                ]);

                if ($validate->fails()) {
                    // dd("a,,ar");
                    return redirect()->back()()->withErrors($validate)->withInput();
                }

                $area = Area::find($request->id);

                if ($area) {

                    $monitors = $request->input('monitors', []);
                    $delivers = $request->input('delivers', []);
                    // dd($monitors);

                    if (
                        $area->AreaMonitors()->syncWithoutDetaching($monitors) &&
                        $area->AreaDelivers()->syncWithoutDetaching($delivers)
                    ) {
                        return redirect()->route("areas.show")->with('success', 'تم التعيين بنجاح');
                    } else {

                        return redirect()->back()()->with('error', 'حصل خطأ غير معروف, الرجاء إعادة المحاولة');
                    }
                } else {
                    return redirect()->back()()->with('error', 'حصل خطأ غير معروف, الرجاء إعادة المحاولة');
                }

            else :
                return redirect()->route("areas.show");
            endif;
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }
}
