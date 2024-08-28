<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth" , "hasAccess"]);
    }

    public function index()
    {
        $flag = "setting";
        return view("panel.dashboard.settings.index" , compact("flag"));
    }


    public function deleted ()
    {
        $flag = "setting";
         
        return view("panel.dashboard.settings.deleted", compact("flag"));
    }
}
