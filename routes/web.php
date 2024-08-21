<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\wep\MonitorController;
use App\Http\Controllers\wep\AdminController;
use App\Http\Controllers\wep\CityController;
use App\Http\Controllers\wep\AreaController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes(['register' => false]);


// Custom Auth
Route::get('/login', [LoginController::class, 'show_login_form'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', [LoginController::class, 'show_signup_form'])->name('register');
Route::post('/register', [LoginController::class, 'process_signup']);

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::get('/test' , function (){
//    return view("test") ;
// });

//Auth::routes();
Route::middleware(["auth", "hasAccess"])->group(function () {
   Route::get('/', [DashboardController::class, 'index']);
   Route::get('/home', [DashboardController::class, 'index'])->name('Admin-Panel');
   Route::get('/404', [DashboardController::class, 'notFound'])->name('404');
   Route::get('/500', [DashboardController::class, 'serverError'])->name('500');
});
Route::get('/403', [DashboardController::class, 'Forbidden'])->name('403');



/**
 * Custom Cities
 */
Route::group(['prefix' => "/cities", 'as' => 'cities.', 'namespace' => "App\Http\Controllers\wep\CityController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Cities
   Route::get('/show', [CityController::class, "index"])->name('show'); // done

   // Edit City
   Route::get('/show/edit', [CityController::class, "edit"])->name("show.city"); //done
   Route::get("/show/cities/edit", [CityController::class, 'showEdit'])->name("edit.city");
   Route::post('/update', [CityController::class, "update"])->name("update.city");

   // Add City
   Route::get("/form/add", [CityController::class, 'create'])->name("add");
   Route::post("/conform/add", [CityController::class, 'conformAdding'])->name("conform.adding");
   Route::post("/store", [CityController::class, 'store'])->name('stor');

   /**
    * Soft Delete 
    */
   Route::post("/delete", [CityController::class, "delete"])->name("soft.delete");
});

/**
 * Custom Areas
 */
Route::group(['prefix' => "/areas", 'as' => 'areas.', 'namespace' => "App\Http\Controllers\wep\AreaController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Areas
   Route::get('/show', [AreaController::class, "index"])->name('show'); // done

   // Edit Area
   Route::get('/show/edit', [AreaController::class, "edit"])->name("edit.area"); //done
   Route::post('/update', [AreaController::class, "update"])->name("update.area");

   // Add Area
   Route::get("/form/add", [AreaController::class, 'create'])->name("add");
   // Route::post("/conform/add", [AreaController::class, 'conformAdding'])->name("conform.adding");
   Route::post("/store", [AreaController::class, 'store'])->name('stor');

   /**
    * Soft Delete 
    */
   Route::post("/delete", [AreaController::class, "delete"])->name("soft.delete");
});



/**
 * Custom Admins
 */
Route::group(['prefix' => "/admins", 'as' => 'admins.', 'namespace' => "App\Http\Controllers\wep\AdminController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Admin
   Route::get('/show', [AdminController::class, "index"])->name('show'); // done

   // Edit Admin
   Route::get('/show/edit', [AdminController::class, "edit"])->name("edit"); //done
   Route::post('/update', [AdminController::class, "update"])->name("update");

   // Add Admin
   Route::get("/form/add", [AdminController::class, 'create'])->name("add");
   // Route::post("/conform/add", [AdminController::class, 'conformAdding'])->name("conform.adding");
   Route::post("/store", [AdminController::class, 'store'])->name('store');

   /**
    * Soft Delete 
    */
   Route::post("/delete", [AdminController::class, "delete"])->name("soft.delete");
});


/**
 * Custom Monitors
 */
Route::group(['prefix' => "/monitors", 'as' => 'monitors.', 'namespace' => "App\Http\Controllers\wep\MonitorsController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Monitor
   Route::get('/show', [MonitorController::class, "index"])->name('show'); // done

   // Edit Monitor
   Route::get('/show/edit', [MonitorController::class, "edit"])->name("edit"); //done
   Route::post('/update', [MonitorController::class, "update"])->name("update");
   Route::get('/active', [MonitorController::class, "active"])->name("active");
   Route::get('/edit/area', [MonitorController::class, "editArea"])->name("edit.area");

   // Add Monitor
   Route::get("/form/add", [MonitorController::class, 'create'])->name("add");
   // Route::post("/conform/add", [MonitorController::class, 'conformAdding'])->name("conform.adding");
   Route::post("/store", [MonitorController::class, 'store'])->name('store');

   /**
    * Soft Delete 
    */
   Route::post("/delete", [MonitorController::class, "delete"])->name("soft.delete");
});
