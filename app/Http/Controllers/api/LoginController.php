<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Traits\GeneralTrait;
use Exception;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use GeneralTrait;
    public function login(Request $request)
    {
        try {
            
            
            $credentials = $request->only('email', 'password');
            if (auth()->attempt($credentials)) {
                $user = auth()->user(); 
                if ($user->type == "client") {

                    $token = $user->createToken('auth_token')->plainTextToken;
                    return $this->apiResponse($token , True , NULL);
                
                } else {
                    return $this->apiResponse(NULL, False, 'You are not a client');
                }
            } else {
                return $this->apiResponse(NULL, False, 'Invalid credentials');
            }


        } catch (Exception $e) {
            return $this->apiResponse(NULL, False, $e->getMessage());
        }
    }

    public function logout (Request $request){
        auth()->user()->tokens()->delete();
        return $this->apiResponse(NULL, True, 'Logged out successfully');
    } // end logout
}
