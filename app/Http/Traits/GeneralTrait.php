<?php

namespace App\Http\Traits;

use App\Models\ContactDetail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

trait GeneralTrait
{
    public function apiResponse($data = null, bool $status = true, $error = null, $statusCode = 200)
    {
        $array = [
            'data' => $data,
            'status' => $status, // True of false 
            'error' => $error, // user not found 
            'statusCode' => $statusCode // 404 500 403 200 303 (headers code)
        ];
        return response($array, $statusCode);
    }

    public function Error($error, $statusCode)
    {
        return $this->apiResponse(null, false, $error, $statusCode);
    }

    public function SuccessResponse($data=null)
    {
        return $this->apiResponse($data, true, null, 200);
    }

    public function ValidationError($validator)
    {
        return $this->apiResponse(null, false, $validator->errors()->first(), 401);
    }

    public function NotFound($error)
    {
        return $this->apiResponse(null, false, $error, 404);
    }

    public function Unauthorized()
    {
        return $this->apiResponse(null, false, 'Unauthorized', 401);
    }

    public function ServerError($error)
    {
        return $this->apiResponse(null, false, $error, 500);
    }

    public function Forbidden()
    {
        return $this->apiResponse(null, false, "Forbidden", 403);
    }

    public function BadRequest($error)
    {
        return $this->apiResponse(null, false, $error, 400);
    }
}
