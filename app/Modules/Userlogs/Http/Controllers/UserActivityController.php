<?php

namespace App\Modules\Userlogs\Http\Controllers;

use Illuminate\Http\Request;
use App\Facade\Creeper;
//use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Userlogs\Models\UserActivity;
use App\Modules\Acl\Models\Admin;
use DataTables;
use Auth, URL;
use Illuminate\Support\Facades\Session;
//use User;
use DB;

class UserActivityController extends Controller
{
    //
    public function index()
    {

        Creeper::canOrFail('browse-userlogs');

        return view('userlogs::UserActivities.index');

    }

    public function show($id)
    {
        $logs = UserActivity::where('user_id', $id)->get();

        if (isset($logs)) {
            $logs = view('userlogs::UserActivities.read', compact('logs'))->render();
            return response()->json($logs, 200);
        }

        return response(404);
    }

    public function getData()
    {
        Creeper::canOrFail('browse-logs');

        $model = UserActivity::query()->with('users');
        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->rawColumns(['check'])
            ->make(true);
    }

    public function getdataUser()
    {


        Creeper::canOrFail('browse-logs');
        if (Auth::guard('admin')->user()->hasRole('superadministrator')) {
            $model = Admin::all();
        } else {
            $model = Admin::where('name', '!=', 'Superadmin')->get();
        }

        return DataTables::collection($model)
            ->addColumn('check', function ($model) {
                if (Auth::guard('admin')->user()->id != $model->id) {
                    return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
                } else {
                    return '<i class="fa fa-user-circle-o" aria-hidden="true"></i>';
                }
            })
            ->addColumn('action', function ($model) {

                return '<button class="btn btn-circle btn-info read" type="button" data-toggle="tooltip" data-original-title="View" data-target="' . route('blog.logs.show',
                        $model->id) . '" onclick="read(this)"> <i class="fa fa-eye"></i> </button>';
            })
            ->rawColumns(['check', 'action'])
            ->make(true);
    }


}
