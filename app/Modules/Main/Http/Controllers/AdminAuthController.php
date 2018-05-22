<?php

namespace App\Modules\Main\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = 'admin/';
    public $maxAttempts = 3; // change to the max attemp you want.
    public $decayMinutes = 120; // change to the minutes you want.

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('main::login');
    }

    public function username()
    {
        return 'name';
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }
}
