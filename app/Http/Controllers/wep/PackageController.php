<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Monitor;
use App\Models\Package;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PackageController extends Controller
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
        $flag =  'packages-show';

        try {
            $query = Package::query();
            $show = $request->input('show', 'show');
            $searchName = $request->input('search_name');
            if ($searchName) {
                $query->where("title", 'LIKE', "%{$searchName}%");
            }
            if ($show == "deleted") {
                $query->onlyTrashed();
            }
            $packages = $query->get();
            return view("panel.dashboard.packages.packages", compact('flag','show' ,'packages', 'searchName'));
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
    public function create()
    {
        $flag =  'packages-create';
        return view("panel.dashboard.packages.add", compact("flag"));
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
                    'title' => 'required|string|max:255|regex:/^[a-zA-Z\s\p{Arabic}]+$/u|unique:packages,title',
                    'orderCount' => 'required|integer|min:1',
                    'totalPrice' => 'required|numeric|min:0',
                ],
                [
                    'title.required' => 'اسم الحزمة مطلوب.',
                    'title.string' => 'اسم الحزمة يجب أن يكون نص.',
                    'title.max' => 'اسم الحزمة لا يمكن أن يتجاوز 255 حرف.',
                    'title.regex' => 'اسم الحزمة يجب أن يحتوي على حروف فقط.',
                    'title.unique' => 'اسم الحزمة موجود بالفعل.',
                    'orderCount.required' => 'عدد الطلبات مطلوب.',
                    'orderCount.integer' => 'عدد الطلبات يجب أن يكون عدد.',
                    'orderCount.min' => 'عدد الطلبات يجب أن يكون على الأقل 1.',
                    'totalPrice.required' => 'السعر الإجمالي مطلوب.',
                    'totalPrice.numeric' => 'السعر الإجمالي يجب أن يكون رقما.',
                    'totalPrice.min' => 'السعر الإجمالي يجب أن يكون على الأقل 0.',
                ]
            );

            if ($validator->fails()) {
                return redirect()->back()->withInput($request->all())->withErrors($validator);
            }

            $package = new Package();
            $package->title = $request->title;
            $package->count_of_orders = $request->orderCount;
            $package->package_price = $request->totalPrice;
            $package->order_price = $request->totalPrice / $request->orderCount;

            if ($package->save()) {
                return redirect()->route("packages.show")->with("success", "تم إضافة الحزمة بنجاح");
            } else {
                return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ, الرجاء إعادة المحاولة");
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
     * Show the form for edit   ing the specified resource.
     */
    public function edit(Request $request)
    {
        try {

            $flag =  'packages-show';
            $validate = Validator::make($request->all(), ['id' => "required"], ['id.required' => " 😢 طلب خاطئ , حاول مرة اخرى",]);
            if ($validate->fails()) {
                return redirect()->back()->withInput($request->all())->withErrors($validate);
            }
            $package = Package::find($request->id);
            if ($package) {
                return view("panel.dashboard.packages.edit", compact("flag", "package"));
            }
            return redirect()->back()->with("error", "طلب خاطئ , حاول مرة اخرى 😢 ");
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

        // التحقق من صحة البيانات
        $validator = Validator::make(
            $request->all(),
            [
                'id' => 'required|exists:packages,id',
                'title' => ['required','string','max:255','regex:/^[a-zA-Z\s\p{Arabic}]+$/u',Rule::unique('packages' , 'title')->ignore($request->id)],
                'orderCount' => 'required|integer|min:1',
                'totalPrice' => 'required|numeric|min:0',
            ],
            [
                'id.required' => 'حصل خطأ غير معروف, الرجاء إعادة المحاولة"',
                'id.exists' => 'حصل خطأ غير معروف, الرجاء إعادة المحاولة"',
                'title.required' => 'اسم الحزمة مطلوب.',
                'title.string' => 'العنوان يجب أن يكون نص.',
                'title.max' => 'العنوان يجب أن لا يزيد عن 255 حرف.',
                'title.regex' => 'العنوان يجب أن يحتوي على حروف فقط.',
                'title.exist' => 'اسم الحزمة موجود بالفعل.',
                'orderCount.required' => 'عدد الطلبات مطلوب.',
                'orderCount.integer' => 'عدد الطلبات يجب أن يكون عدد.',
                'orderCount.min' => 'عدد الطلبات يجب أن يكون على الأقل 1.',
                'totalPrice.required' => 'السعر الإجمالي مطلوب.',
                'totalPrice.numeric' => 'السعر يجب أن يكون رقم.',
                'totalPrice.min' => 'السعر يجب أن يكون على الأقل 0.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all());
        }

        try {

            $package = Package::findOrFail($request->id);

            $package->title = $request->input('title');
            $package->count_of_orders = $request->input('orderCount');
            $package->package_price = $request->input('totalPrice');
            $package->order_price = $request->input('totalPrice') / $request->input('orderCount');



            if (!$package->save()) {
                return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
            }

            return redirect()->route('packages.show')->with('success', 'تم تحديث الحزمة بنجاح.');
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ ��ير معروف, الرجاء إعادة المحاولة");
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


            if (Package::find($request->id)->delete()) {
                return redirect()->back()->with("success", "تم حذف الحزمة بنجاح");
            }
            return redirect()->back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

    public function restore(Request $request) {
        $validate = Validator::make(['id' => $request->id], ['id' => "required"], [
            'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة"
        ]);
        if ($validate->fails()) {
            return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
        }
        try {
            $package = Package::onlyTrashed()->find($request->id);
            if ($package->restore()) {
                return redirect()->back()->with("success", "تم استرجاع الحزمة بنجاح");
            }
            return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }
    }

}
