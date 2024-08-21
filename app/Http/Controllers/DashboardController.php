<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        $PageTitle = 'Dashboard';
        $city = [];
        return view('panel.Dashboard.index',
            compact('PageTitle', 'city' )
        );
    }

    public function notFound()
    {
        return view('error.404');
    }

    public function serverError()
    {
        return view('error.500');
    }
    public function Forbidden ()
    {
        return view('error.403');
    }

    public function test()
    {
        return view('test');
    }
}
