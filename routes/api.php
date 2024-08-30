<?php

use App\Http\Controllers\api\CityController;
use App\Http\Controllers\api\LoginController;
use App\Http\Resources\CityResource;
use App\Http\Resources\UserResource;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Auth Routes
 */
Route::post("/login",[LoginController::class, "login"]);
Route::post("/signup", [LoginController::class, "signUp"]);
Route::middleware("auth:sanctum")->post("/logout", [LoginController::class, "logout"]);
Route::get("/show/cities" , [CityController::class , "index"]) ;



Route::middleware("auth:sanctum")->group(function(){

    Route::middleware("auth:sanctum")->get("/show/city/{id}" , [CityController::class , "show"]) ;
    
    Route::get("/cities" , function () {
        return CityResource::collection(City::all());
    });
});

