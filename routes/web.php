<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\wep\MonitorController;
use App\Http\Controllers\wep\AdminController;
use App\Http\Controllers\wep\CityController;
use App\Http\Controllers\wep\AreaController;
use App\Http\Controllers\wep\DeliverController;
use App\Http\Controllers\wep\EmployController;
use App\Http\Controllers\wep\PackageController;
use App\Http\Controllers\wep\ClientController;
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
Route::middleware(['auth', 'hasAccess'])->group(function () {
   Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
   Route::get('/home', [DashboardController::class, 'index'])->name('Admin-Panel');
});


/**
 * errors
 */
Route::view('/404', 'error.404')->name('404');
Route::view('/500', 'error.500')->name('500');
Route::view('/403', "error.403")->name('403');




/**
 * Custom Cities
 */
Route::group(['prefix' => "/cities", 'as' => 'cities.', 'namespace' => "App\Http\Controllers\wep\CityController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Cities
   Route::get('/show', [CityController::class, "index"])->name('show'); // done

   Route::middleware('isAdmin')->group(function () {

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
});

/**
 * Custom Areas
 */
Route::group(['prefix' => "/areas", 'as' => 'areas.', 'namespace' => "App\Http\Controllers\wep\AreaController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Areas
   Route::get('/show', [AreaController::class, "index"])->name('show'); // done

   Route::middleware('isAdmin')->group(function () {

      // Edit Area
      Route::get('/show/edit', [AreaController::class, "edit"])->name("edit.area"); //done
      Route::post('/update', [AreaController::class, "update"])->name("update.area");

      // Add Area
      Route::get("/form/add", [AreaController::class, 'create'])->name("add");
      Route::get("/form/add/employs", [AreaController::class, 'createEmploys'])->name("add.employs");
      Route::post("/add/employs", [AreaController::class, 'storeEmploys'])->name("store.employs");
      Route::post("/store", [AreaController::class, 'store'])->name('stor');

      /**
       * Soft Delete 
       */
      Route::post("/delete", [AreaController::class, "delete"])->name("soft.delete");
   });
});



/**
 * Custom Admins
 */
Route::group(['prefix' => "/admins", 'as' => 'admins.', 'namespace' => "App\Http\Controllers\wep\AdminController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Admin
   Route::get('/show', [AdminController::class, "index"])->name('show'); // done


   Route::middleware('isAdmin')->group(function () {

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
});


/**
 * Custom Monitors
 */
Route::group(['prefix' => "/monitors", 'as' => 'monitors.', 'namespace' => "App\Http\Controllers\wep\MonitorController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Monitor
   Route::get('/show', [MonitorController::class, "index"])->name('show'); // done

   Route::middleware('isAdmin')->group(function () {

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
});


/**
 * Custom Delivers
 */
Route::group(['prefix' => "/delivers", 'as' => 'delivers.', 'namespace' => "App\Http\Controllers\wep\DeliverController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all Delivers
   Route::get('/show', [DeliverController::class, "index"])->name('show');

   Route::middleware('isAdmin')->group(function () {

      // Edit Delivers
      Route::get('/show/edit', [DeliverController::class, "edit"])->name("edit");
      Route::post('/update', [DeliverController::class, "update"])->name("update");

      // Add Delivers
      Route::get("/form/add", [DeliverController::class, 'create'])->name("add");
      Route::post("/store", [DeliverController::class, 'store'])->name('store');

      // Soft Delete 
      Route::post("/delete", [DeliverController::class, "delete"])->name("soft.delete");
   });
});

/**
 * Add Employs
 */
Route::group(['prefix' => "/employs", 'as' => 'employs.', 'namespace' => "App\Http\Controllers\wep\EmployController", "middleware" => ["auth", "isAdmin"]], function () {
   Route::get("/form/add/employ", [EmployController::class, 'createEmploys'])->middleware("isAdmin")->name("create");
   Route::post("/store/employ", [EmployController::class, 'storeEmploys'])->middleware("isAdmin")->name('store');
});





/**
 * Custom Admins
 */
Route::group(['prefix' => "/packages", 'as' => 'packages.', 'namespace' => "App\Http\Controllers\wep\PackageController", "middleware" => ["auth", "hasAccess"]], function () {
   // Show all package

   Route::get('/show', [packageController::class, "index"])->name('show'); // done

   Route::middleware('isAdmin')->group(function () {

      // Edit package
      Route::get('/show/edit', [packageController::class, "edit"])->name("edit"); //done
      Route::post('/update', [packageController::class, "update"])->name("update");

      // Add package
      Route::get("/form/add", [PackageController::class, 'create'])->name("add");
      // Route::post("/conform/add", [packageController::class, 'conformAdding'])->name("conform.adding");
      Route::post("/store", [packageController::class, 'store'])->name('store');

      /**
       * Soft Delete 
       */
      Route::post("/delete", [packageController::class, "delete"])->name("soft.delete");
   });
});


/**
 * Custom Client
 */
Route::group(['prefix' => "/clients", 'as' => 'clients.', 'namespace' => "App\Http\Controllers\wep\ClientController", "middleware" => ["auth", "hasAccess"]], function () {
   Route::get('/show', [ClientController::class, "index"])->name('show'); 

   Route::middleware('isAdmin')->group(function () {

      // Edit package
      Route::get('/show/edit', [ClientController::class, "edit"])->name("edit"); 
      Route::post('/update', [ClientController::class, "update"])->name("update");

      // Add package
      Route::get("/form/add", [ClientController::class, 'create'])->name("add");
      Route::post("/store", [ClientController::class, 'store'])->name('store');

      /**
       * Soft Delete
       */
      Route::post("/delete", [ClientController::class, "delete"])->name("soft.delete");
   });
});
 