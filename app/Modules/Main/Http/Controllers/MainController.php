<?php

namespace App\Modules\Main\Http\Controllers;

use App\Facade\Creeper;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

class MainController extends Controller
{

    public function __construct()
    {
        $this->middleware('admin')->except('logout');
    }

    public function index()
    {

        Creeper::canOrFail('access-dashboard');

        return view('main::dashboard');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
