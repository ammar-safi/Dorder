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
    public function __construct()
    {
        $this->middleware('guest')->except("logout");
    }


    public function login(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:7',
        ]);

        if ($validator->fails()) {
            return $this->ValidationError($validator);
        }
        
        try {

            if (Auth::attempt(["email" => $request->email, "password" => $request->password])) {
                $user = Auth::user();
                if ($user->type == "client") {
                    $token = $user->createToken('token')->plainTextToken;
                    $data["user"] = LoginResource::make($user);
                    $data["token"] = $token;
                    return $this->SuccessResponse($data);
                } else {
                    return $this->Forbidden();
                }
            } else {
                return $this->apiResponse(NULL, False, "الايميل او كلمة المرور غير صحيحة", 401);
            }
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
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
            return $this->ValidationError($validator);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'type' => 'client',
                'password' => $request->password,
                'uuid' => \Str::uuid(),
            ]);

            if ($user) {
                $token = $user->createToken('token')->plainTextToken;
                $data["user"] = SignupResource::make($user);
                $data["token"] = $token;
                return $this->SuccessResponse($data);
            } else {
                return $this->Unauthorized();
            }
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }


    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
            return $this->SuccessResponse(NULL);
        } catch (Exception $e) {
            return $this->ServerError($e->getMessage());
        }
    }
}
