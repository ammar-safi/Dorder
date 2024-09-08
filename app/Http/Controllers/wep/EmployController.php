<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\City;
use App\Models\User;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class EmployController extends Controller
{

    public function __construct()
    {
        $this->middleware("isAdmin");
    }


    public function createEmploys(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "city_id" => "nullable|exists:cities,id",
                "area_id" => "exists:areas,id",
            ],
            [
                'id.exist' => "حصل خطأ غير متوقع",
                "city_id.exists" => "",
                "area_id.exists" => "",
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }
        try {


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

            $cities = City::all();
            $areas = $request->input("city_id") ? Area::where("city_id", $request->input("city_id"))->get() : "";
            // dd($request->input("area_id"));
            $selectedCityId = $request->input("city_id") ? $request->input("city_id") : '';
            $selectedAreaId = $request->input("area_id") ? $request->input("area_id") : '';

            $route = $request->route;
            return view("panel.dashboard.employ.addEmploys", compact('route', "areas", 'cities', 'selectedCityId', 'selectedAreaId', 'monitors', 'delivers'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء تحديث المشرف. يرجى المحاولة لاحقاً.');
        }
    }


    public function storeEmploys(Request $request)
    {
        try {
            // if (($request->input('monitors') || $request->input("delivers")) && $request->input("id")) :

            $validate = Validator::make($request->all(), [
                'id' => 'required|exists:areas,id',
                'monitors' => 'required_without_all:delivers|array',
                'monitors.*' => [
                    Rule::exists('users', 'id')->where(function (Builder $query) {
                        return $query->where('type', 'monitor')->whereNull('deleted_at');
                    }),
                    Rule::unique('monitors', 'monitor_id')->where(function (Builder $query) use ($request) {
                        return $query->where('area_id', $request->id)->whereNull('deleted_at');
                    }),
                ],


                'delivers' => 'required_without_all:monitors|array',
                'delivers.*' => [
                    Rule::exists('users', 'id')->where(function (Builder $query) {
                        return $query->where('type', 'deliver');
                    }),
                    // عامل التوصيل ما بصير يشتغل باكتر من منطقة
                    Rule::unique('delivers', 'deliver_id')
                ],


            ], [
                'id.required' => "يجب ان تقوم بتحديد المدينة والمنطقة ",
                'id.exists' => 'المنطقة المحددة غير موجودة',
                'monitors.array' => 'بيانات المشرفين غير صحيحة',
                'monitors.required_without_all' => "يجب ان تختار على الاقل مشرف او عامل توصيل",
                'monitors.*.exists' => "هنالك خطأ , هذا المشرف غير موجود",
                'monitors.*.unique' => 'المشرف يعمل في هذه المنطقة بالفعل',
                'delivers.array' => 'بيانات عمال التوصيل غير صحيحة',
                'delivers.required_without_all' => "يجب ان تختار على الاقل مشرف او عامل توصيل",
                'delivers.*.unique' => 'عامل التوصيل هذا غير متاح ',
                'delivers.*.exists' => '  هنالك خطأ , عامل توصيل غير موجود',
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate)->withInput();
            }

            $area = Area::find($request->id);
            if ($area) {
                $monitors = $request->input('monitors', []);
                $delivers = $request->input('delivers', []);

                if (
                    $area->AreaMonitors()->syncWithoutDetaching($monitors) &&
                    $area->AreaDelivers()->syncWithoutDetaching($delivers)
                ) {
                    return redirect()->route($request->route)->with('success', 'تم التعيين بنجاح');
                } else {

                    return redirect()->back()->with('error', 'حصل خطأ غير معروف, الرجاء إعادة المحاولة');
                }
            } else {
                return redirect()->back()->with('error', 'حصل خطأ غير معروف, الرجاء إعادة المحاولة');
            }

            // else :
            //     return redirect()->route("areas.show");
            // endif;
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }
}
