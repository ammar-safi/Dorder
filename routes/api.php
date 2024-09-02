<?php

use App\Http\Controllers\api\CityController;
use App\Http\Controllers\api\ClientController;
use App\Http\Controllers\api\LoginController;
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
Route::middleware("guest")->group(function () {
    Route::post("/login", [LoginController::class, "login"]);
    Route::post("/signup", [LoginController::class, "signUp"]);
});
Route::post("/logout", [LoginController::class, "logout"])->middleware("auth:sanctum");

/*
  ********************
 **** Cities Routes *****
  ********************
*/
Route::group(['prefix' => 'cities', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/', [CityController::class, 'index']);
    Route::post('/add', [CityController::class, 'store']);
});
Route::group(['prefix' => 'clients', 'middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', [ClientController::class, 'index']);
    Route::get('/edit', [ClientController::class, 'show']);
    Route::post('/edit', [ClientController::class, 'update']);
});
