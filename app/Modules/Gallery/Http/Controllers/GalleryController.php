<?php

namespace App\Modules\Gallery\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Facade\Creeper;

class GalleryController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        Creeper::canOrFail('browse-galleries');

        return view('gallery::index');
    }
}
