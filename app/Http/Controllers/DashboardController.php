<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\City;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct() {}

    public function index()

    {
        $PageTitle = 'Dashboard';
        $flag = 'home';
        $citiesCount = City::count();

        $areasCount = Area::count();

        $clientsCount = User::where('type', 'client')->count();

        $monitorsCount = User::where('type', 'monitor')->count();

        $deliversCount = User::where('type', 'deliver')->count();

        return view('panel.Dashboard.index', compact('PageTitle', 'flag', 'citiesCount', 'areasCount', 'clientsCount', 'monitorsCount', 'deliversCount'));
    }



    public function notFound()
    {
        return view('error.404');
    }

    public function serverError()
    {
        return view('error.500');   
    }
    public function Forbidden()
    {
        return view('error.403');
    }

    public function test()
    {
        return view('test');
    }
}
