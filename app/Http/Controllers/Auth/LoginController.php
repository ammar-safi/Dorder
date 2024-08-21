<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function show_login_form()
    {
        
        if ( Auth::user() && ( Auth::user()->type == 'admin' || Auth::user()->type == 'monitor') ) {
            $this->redirectTo = '/admin-panel';
            return redirect()->route('Admin-Panel');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'UserMail' => 'required',
            'password' => 'required',
        ]);

        $login = request()->input('UserMail');

        if (is_numeric($login)) {
            $request['mobile'] = $login;
        } elseif (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $request['email'] = $login;
        } else {
            $request['email'] = $login;
        }

        $credentials = $request->except(['_token', 'remember', 'UserMail']);
        if (auth()->attempt($credentials)) {
            $user = User::where('email', $request->UserMail)->orWhere('mobile', $request->UserMail)->first();
            if ($user->type == 'admin') {
                $this->redirectTo = '/admin-panel';
                return redirect()->route('Admin-Panel');
            } else if ($user->type == 'monitor') {
                return redirect()->route('Admin-Panel');
            } else {
                return redirect()->back()->withErrors(['email', 'Invalid credentials'])->withInput()->with('message', 'خطأ في الصلاحية');
            }
        } else {
            return redirect()->back()->withErrors(['email', 'Invalid credentials'])->withInput()->with('message', 'خطأ في البيانات');
        }
    }



    public function show_signup_form()
    {
        return view('auth.register');
    }

    public function process_signup(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::create([
            'name' => trim($request->input('name')),
            'email' => strtolower($request->input('email')),
            'password' => bcrypt($request->input('password')),
        ]);

        session()->flash('message', 'Your account is created');

        return redirect()->route('login');
    }


    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }
}
