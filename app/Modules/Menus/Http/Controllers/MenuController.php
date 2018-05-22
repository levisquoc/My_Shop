<?php

namespace App\Modules\Menus\Http\Controllers;

use App\Facade\Creeper;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Page;
use App\Modules\Posts\Models\Category;

class MenuController extends Controller
{
    //
    public function index()
    {
        Creeper::canOrFail('manage-menu');
        return view('menus::index');
    }

    public function selectModule(Request $request)
    {
        $module = $request->module;
        if ($module == 'page') {
            $data = Page::all();
            $view = view('menus::option_render', compact('data', 'module'))->render();
            return response()->json($view);
        } else {
            $data = Category::all();
            $view = view('menus::option_render', compact('data', 'module'))->render();
            return response()->json($view);
        }
    }
}
