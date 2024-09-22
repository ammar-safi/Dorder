<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "hasAccess"]);
        $this->middleware("isAdmin")->except("index");
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $flag = "show-admins";
            $query = User::query()->where("type", "admin");
            $deleted = request()->deleted;
            $searchName = request()->search_name ;
            if ($deleted) {
                $query->onlyTrashed();
            }
            if ($searchName) {
                $query->where('name', 'LIKE', '%' . $searchName . '%');
            }
            $admins = $query->get();
            return view("panel.dashboard.admins.admins", compact("flag", "admins", "deleted" , "searchName"));
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
            $flag = "add-admin";
            return view("panel.dashboard.admins.add", compact("flag"));
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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|regex:/^[\p{Arabic}\s]+$/u|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'mobile' => 'required|nullable|regex:/^09[0-9]{8}$/|string|max:20|unique:users,mobile',
                "active" => 'nullable|boolean',
            ], [
                'name.required' => 'الرجاء إدخال اسم المدير.',
                'name.regex' => 'الرجاء إدخال اسم المدير باللغة العربية فقط.',
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

            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }


            if (User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'mobile' => $request->mobile,
                'uuid' => Str::uuid(),
                'type' => 'admin',
                'active' => $request->active ? 1 : 0,
            ])) {
                return redirect()->route("admins.show")->with("update_success", "تمت الاضافة  بنجاح");
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
                ['id' => $request->id],
                [
                    'id' => [
                        "required",
                        // "not_in:1",
                        Rule::exists("users", "id")->where('type', 'admin')
                    ]
                ],
                [
                    'id.required' => "حصل خطأ غير متوقع",
                    'id.exists' => "حصل خطأ غير متوقع",
                    // "id.not_in" => "لا يمكنك تعديل هذا المدير"
                ]
            );

            if ($validate->fails()) {
                return redirect()->back()->with("error", "حصل خطأ غير متوقع, الرجاء المحاولة لاحقا");
            }

            if ($request->id == '1' && Auth::User()->id != 1) {
                return redirect()->back()->with("error", "حصل خطأ غير متوقع, الرجاء المحاولة لاحقا");
            }

            $admin = User::find($request->id);

            if ($admin->type == "admin") {
                return view("panel.dashboard.admins.edit", compact('admin'));
            } else {
                return redirect()->back()->with("error", "حدث خطأ غير معروف , اعد المحاولة لاحقا");
            }
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", $e->getMessage());
        }


        // if (!$admin && $admin->type != "admin") {
        //    return redirect()->back()->with("error", "حصل خطأ غير متوقع , حاول مرة اخرى ");
        // }
        // return view("panel.dashboard.admins.edit",  compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|not_in:1',
                'name' => 'required|string|regex:/^[\p{Arabic}\s]+$/u|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique("users", "email")->where("type", "admin")->ignore($request->email, 'email'),
                ],
                'mobile' => [
                    'required',
                    'nullable',
                    'string',
                    'max:20',
                    'regex:/^09[0-9]{8}$/',
                    Rule::unique("users", "mobile")->where("type", "admin")->ignore($request->mobile, 'mobile'),
                ],
            ], [
                'id.required' => "حصل خطأ غير متوقع",
                "id.not_in" => "لا يمكنك تعديل هذا المدير",

                'name.required' => 'الرجاء إدخال اسم المدير.',
                'name.regex' => 'الرجاء إدخال اسم المدير باللغة العربية فقط.',
                'name.string' => 'يجب أن يكون الاسم نصًا.',
                'name.max' => 'لا يمكن أن يتجاوز طول الاسم 255 حرفًا.',

                'email.required' => 'الرجاء إدخال البريد الإلكتروني.',
                'email.email' => 'الرجاء إدخال بريد إلكتروني صالح.',
                'email.unique' => 'البريد الإلكتروني مُستخدم بالفعل.',

                'mobile.required' => 'الرجاء إدخال رقم الهاتف.',
                'mobile.string' => 'يجب أن يكون رقم الهاتف نصًا.',
                'mobile.max' => 'لا يمكن أن يتجاوز طول رقم الهاتف 20 حرفًا.',
                "mobile.unique" => "هذا الرقم قيد الاستخدام بالفعل",
                'mobile.regex' => 'رقم الهاتف غير صالح.',

            ]);

            if ($validator->fails()) {
                // dd("test");
                return redirect()->back()->withErrors($validator)->withInput($request->all);
            }

            $admin = User::find($request->id);

            if ($admin && $admin->type == "admin"  && $admin->update([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
            ])) {
                return redirect()->route("admins.show")->with("success", "تمت التعديل  بنجاح");
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function delete(Request $request)
    {
        try {
            $validate = Validator::make(['id' => $request->id], ['id' => "required|not_in:1"], [
                'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة",
                "id.not_in" => "لا يمكنك تعديل هذا المدير",

            ]);
            if ($validate->fails()) {
                return redirect()->back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
            }


            $is_exist = user::find($request->id);
            if ($is_exist && $is_exist->delete()) {
                return redirect()->back()->with("success", "تم الحذف بنجاح");
            }
            return redirect()->back()->with("error", "حصل خطأ غير معروف , حاول مرة اخرى");
        } catch (Exception $e) {
            Log::error("حدث خطأ: " . $e->getMessage(), [
                'exception' => $e
            ]);
            return redirect()->back()->with("error", "حصل خطأ غير معروف, الرجاء إعادة المحاولة");
        }
    }
    public function restore(Request $request)
    {
        try {
            $validate = Validator::make(['id' => $request->id], ['id' => "required|not_in:1"], [
                'id.required' => "حصل خطأ غير معروف , الرجاء اعادة المحاولة",
                "id.not_in" => "لا يمكنك تعديل هذا المدير",

            ]);
            if ($validate->fails()) {
                return redirect()->back()->with("error", "حصل خطأ غير معروف, حاول مرة اخرى");
            }


            $is_exist = user::where("type", "admin")->onlyTrashed()->find($request->id);
            if ($is_exist && $is_exist->restore()) {
                return redirect()->back()->with("success", "تمت الاستعادة بنجاح");
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
