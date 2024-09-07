<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDashboardResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\GeneralTrait;
use App\Models\Product;
use App\Models\User;
use App\Models\UserActivation;
use App\Models\UserToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use GeneralTrait;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email,' . $request->user_id,
            'password' => 'nullable|required_without:registration_method|min:8',
            'registration_method' => 'nullable|required_without:password|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_number' => 'required|string',
            'country_code' => 'required|string',

        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('email')) {
                return $this->apiResponse(null, false, 'Email already exists', 405);
            }
            return $this->requiredField($validator->errors()->first());
        }

        try {

            // Create new user
            $user = User::create([
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : null,
                'status' => 0,
                'social_id' => $request->social_id,
                'registration_method' => $request->registration_method ?: 'manual',
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone_number' => $request->phone_number,
                'country_code' => $request->country_code,
            ]);


            if (!$request->registration_method) {
                $uniqueId = uniqid();
                $randomToken = crc32($uniqueId) % 100000;
                $randomToken = ($randomToken < 0) ? -$randomToken : $randomToken;
                $randomToken = str_pad($randomToken, 5, '0', STR_PAD_LEFT);
                UserActivation::create([
                    'user_id' => $user->id,
                    'email' => $request->email,
                    'token' => $randomToken,
                    'status' => 0,
                ]);

                $templateName = "emails.activation_code";
                $email = $request->email;
                $subj = "Activation Link";
                $userData = [
                    'user' => $user,
                    'token' => $randomToken,
                ];
            } else {
                $templateName = "emails.account-created";
                $email = $user->email;
                $subj = "Your Account Has Been Created";
                $userData = [
                    'user' => $user,
                ];
            }

            $result = $this->send_email($templateName, $email, $subj, $userData);

            if ($result === true) {
                $data['user_id'] = $user->id;
                $data['message'] = 'Registration successful! An email containing the activation code has been sent to your email address. Please check your inbox to activate your account.';
                return $this->apiResponse($data);
            } else {
                return $this->apiResponse(null, false, 'Failed to send activation email, please try again.', 503);
            }
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }



    public function login(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'nullable|required_without:registration_method',
            'registration_method' => 'nullable|required_without:password|string',

            'token_device' => 'required|string',
        ]);

        // Check if validation fails and return errors if any
        if ($validator->fails()) {
            return $this->requiredField($validator->errors()->first());
        }

        try {
            // Attempt to find the user by phone number
            $user = User::where('email', $request->input('email'))->first();

            // Verify the phone
            if (!$user) {
                return $this->apiResponse(null, false, 'Invalid email Or Password .', 400);
            }

            if ($request->filled('password')) {
                if (!Hash::check($request->input('password'), $user->password)) {
                    return $this->apiResponse(null, false, 'Invalid email or password.', 400);
                }
            }

            if ($request->filled('registration_method')) {
                if ($user->registration_method !== $request->input('registration_method')) {
                    return $this->apiResponse(null, false, 'Invalid registration method.', 400);
                }
            }



            $userToken = $user->token_devices()->firstOrCreate([
                'token_device' => $request->token_device
            ]);

            // Generate a token for the user
            $data['user'] = new UserResource($user);
            $data['token'] = $user->createToken('MyApp')->plainTextToken;


            return $this->apiResponse($data, true, null, 200);
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }



    public function logout(Request $request)
    {
        try {
            $user = auth('sanctum')->user();

            if ($user) {
                $token = $user->currentAccessToken();

                $token->delete();

                // Delete UserToken record for the device token
                $tokenDevice = $request->input('token_device');
                if ($tokenDevice) {
                    UserToken::where('user_id', $user->id)
                        ->where('token_device', $tokenDevice)
                        ->delete();
                }

                return $this->apiResponse([], true, null, 200);
            } else {
                return $this->unAuthorizeResponse();
            }
        } catch (\Exception $ex) {
            return $this->apiResponse(null, false, $ex->getMessage(), 500);
        }
    }
}
