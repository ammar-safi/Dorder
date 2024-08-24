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
        // جلب عدد المدن
        $citiesCount = City::count();

        // جلب عدد المناطق
        $areasCount = Area::count();

        // جلب عدد العملاء
        $clientsCount = User::where('type', 'client')->count();

        // جلب عدد المشرفين
        $monitorsCount = User::where('type', 'monitor')->count();

        // جلب عدد عمال التوصيل
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
