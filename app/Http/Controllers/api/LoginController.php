<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource\LoginResource;
use App\Http\Resources\ClientResource\SignupResource;
use App\Http\Traits\GeneralTrait;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    use GeneralTrait;


    public function login(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:7',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(NULL, False, $validator->errors()->first(), 401);
        }

        try {

            if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
                $user = Auth::user();
                if($user->type == "client") {
                    $token = $user->createToken('token')->plainTextToken;
                    $data["user"] = LoginResource::make($user) ;
                    $data["token"] = $token ;
                    return $this->apiResponse($data, True, null, 200);


                } else {
                    return $this->apiResponse(NULL, False, "الدخول غير مسموح", 403);
                }
            } else {
                return $this->apiResponse(NULL, False, "الايميل او كلمة المرور غير صحيحة", 401);
            }
        } catch (Exception $e) {
            return $this->apiResponse('', False, $e->getMessage(), 500);
        }
    }


    public function signUp(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'name' => 'required',
            "mobile" => ['required', Rule::unique('users', 'mobile')->where("type", "client")],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:7',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(NULL, False, $validator->errors()->first(), 401);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'type' => 'client',
                'password' => $request->password,
                'uuid' => \Str::uuid() ,
            ]);

            if ($user) {
                $token = $user->createToken('token')->plainTextToken;
                $data["user"] = SignupResource::make($user) ;
                $data["token"] = $token ;
                return $this->apiResponse($data, True, null , 200);
            } else {
                return $this->apiResponse("", False, "حدث خطا , الرجاء اعادة المحاولة", 401);
            }
        } catch (Exception $e) {
            return $this->apiResponse('', False, $e->getMessage(), 500);
        }
    }


    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->apiResponse(NULL, True, 'تم تسجيل الخروج');
        } catch (Exception $e) {
            return apiResponse();
        }
    }
}
