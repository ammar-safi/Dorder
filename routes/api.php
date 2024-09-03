<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrderController;
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

/*
  ********************
 **** Clients Routes *****
  ********************
*/
Route::group(['prefix' => 'clients', 'middleware' => ['auth:sanctum', "isClient"]], function () {
  Route::get('/profile', [ClientController::class, 'index']);
  Route::get('/edit', [ClientController::class, 'show']);
  Route::post('/edit', [ClientController::class, 'update']);
});

/*
  *********************
 **** Orders Routes *****
  *********************
*/
Route::group(['prefix' => 'orders', 'middleware' => ['auth:sanctum', "isClient"]], function () {
  Route::get('/', [OrderController::class, 'index']);
  Route::get('/add', [OrderController::class, 'create']);
  Route::post('/add', [OrderController::class, 'store']);

});


/*
  *********************
 **** Addresses Routes *****
  *********************
*/
Route::group(['prefix' => 'addresses', 'middleware' => ['auth:sanctum', "isClient"]], function () {
  Route::get('/', [AddressController::class, 'index']);
  Route::post('/add', [AddressController::class, 'store']);
});
