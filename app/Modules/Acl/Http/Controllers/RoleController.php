<?php

namespace App\Modules\Acl\Http\Controllers;

use App\Facade\Creeper;
use App\Helpers\ErrorHelpers;
use App\Modules\Acl\Models\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Acl\Models\Role;
use DataTables;
use Illuminate\Support\Facades\Session;
use URL;
use Auth;
use App\Helpers\UserActivityHelpers;

class RoleController extends Controller
{
    use ErrorHelpers;
    use UserActivityHelpers;

    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Creeper::canOrFail('browse-roles');

        return view('acl::Roles.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-roles');

        $permissions = Permission::all()->groupBy('resource');

        return view('acl::Roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Creeper::canOrFail('create-roles');

        $this->validate($request, [
            'display_name' => 'required|max:255',
            'name' => 'required|max:100|alpha_dash|unique:roles,name',
            'description' => 'sometimes|max:255'
        ]);
        $role = new Role();
        $role->display_name = $request->display_name;
        $role->name = $request->name;
        $role->description = $request->description;


        $role->save();
        //user activity
        $action = "Create";
        $object = "Role";
        $object_id = null;
        $value_before = null;
        $value_after = "Name:" . $request->name .
            "," .
            "Display_name:" . $request->display_name .
            "," .
            "Description:" . $request->description;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //user activity

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Successfully created the new ' . $role->display_name . ' role in the database.');
        return redirect()->route('acl.roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Creeper::canOrFail('read-roles');

        $role = Role::findOrFail($id);

        if ($role) {

            $permissions = $role->permissions->groupBy('resource');

            return view('acl::Roles.read', compact('role', 'permissions'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Creeper::canOrFail('update-roles');

        $role = Role::findOrFail($id);

        if ($role) {
            $permissions = Permission::all()->groupBy('resource');

            return view('acl::Roles.edit', compact('permissions', 'role'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Creeper::canOrFail('update-roles');

        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255'
        ]);
        $role = Role::findOrFail($id);
        $role->display_name = $request->display_name;
        $role->description = $request->description;

        //user activity
        $action = "Edit";
        $object = "Role";
        $object_id = $id;
        $value_before = "Name:" . $role->name .
            "," .
            "Display_name:" . $role->display_name .
            "," .
            "Description:" . $role->description;

        $value_after = "Name:" . $request->name .
            "," .
            "Display_name:" . $request->display_name .
            "," .
            "Description:" . $request->description;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity
        $role->save();


        $role->syncPermissions($request->permissions);

        Session::flash('status_type', 'success');
        Session::flash('status', 'Successfully update the ' . $role->display_name . ' role in the database.');
        return redirect()->route('acl.roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Creeper::canOrFail('delete-roles');

        $role = Role::findOrFail($id);
        if ($role) {
            // Force Delete
            $role->admins()->sync([]); // Delete relationship data
            $role->permissions()->sync([]); // Delete relationship data
            //user activity
            $action = "Delete";
            $object = "Role";
            $object_id = $id;
            $value_before = "Name:" . $role->name .
                "," .
                "Display_name:" . $role->display_name .
                "," .
                "Description:" . $role->description;
            $value_after = null;
            UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
            //end user activity
            $role->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            return redirect()->back()->with([
                'status_type' => 'success',
                'status' => 'Role has been successfully deleted'
            ]);
        }
        $url = URL::previous();
        return $this->notFound($url);

    }

    /**
     *
     * Delete select roles
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSelected(Request $request)
    {

        Creeper::canOrFail('delete-roles');

        $ids = $request->data;
        foreach ($ids as $id) {
            $role = Role::findOrFail($id);
            if ($role) {
                // Force Delete
                $role->admins()->sync([]); // Delete relationship data
                $role->permissions()->sync([]); // Delete relationship data

                $role->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Roles has been successfully deleted');
        return response()->json(['status' => 'Roles has been successfully deleted'], 200);
    }

    public function getData()
    {
        Creeper::canOrFail('browse-roles');

        if (Auth::guard('admin')->user()->hasRole('superadministrator')) {
            $model = Role::all();
        } else {
            $model = Role::where('name', '!=', 'superadministrator')->get();
        }

        return DataTables::collection($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->addColumn('action', function ($model) {
                return '<a class="btn btn-circle btn-info read" href="' . route('acl.roles.show', $model->id) . '" data-toggle="tooltip" data-original-title="View"> <i class="fa fa-eye"></i> </a>
                        <a class="btn btn-circle btn-success edit" href="' . route('acl.roles.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                        <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action'])
            ->make(true);
    }
}
