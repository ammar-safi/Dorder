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
}
