<?php

namespace App\Modules\Acl\Http\Controllers;

use App\Facade\Creeper;
use App\Helpers\ErrorHelpers;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Acl\Models\Permission;
use DataTables;
use Illuminate\Support\Facades\Session;
use URL;
use App\Helpers\UserActivityHelpers;

class PermissionController extends Controller
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
        Creeper::canOrFail('browse-permissions');

        return view('acl::Permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-permissions');

        return view('acl::Permissions.create');
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
        Creeper::canOrFail('create-permissions');

        if ($request->permission_type == "basic") {
            $this->validate($request, [
                'display_name' => 'required|max:255',
                'name' => 'required|max:255|alphadash|unique:permissions,name',
                'description' => 'sometimes|max:255'
            ]);
            $permission = new Permission();
            $permission->name = $request->name;
            $permission->display_name = $request->display_name;
            $permission->description = $request->description;

            if ($permission->save()) {
                //user activity
                $action = "Create Basic";
                $object = "Permission";
                $object_id = null;
                $value_before = null;
                $value_after = "Name:" . $request->name .
                    "," .
                    "Display_Name:" . $request->display_name .
                    "," .
                    "Description:" . $request->description;

                UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
                //user activity
                return redirect()->route('acl.permissions.index')->with([
                    'status_type' => 'success',
                    'status' => 'Permission has been successfully added'
                ]);
            }


            return redirect()->back()->withInput()->with([
                'status_type' => 'error',
                'status' => 'Some thing wrong please contact to admin for support'
            ]);

        } elseif ($request->permission_type == 'crud') {
            $this->validate($request, [
                'resource' => 'required|min:3|max:191|alpha|unique:permissions,resource'
            ]);
            $crud = explode(',', $request->crud_selected);
            if (count($crud) > 0) {
                foreach ($crud as $x) {
                    $slug = strtolower($x) . '-' . strtolower($request->resource);
                    $display_name = ucwords($x . " " . $request->resource);
                    $description = "Allows a user to " . strtoupper($x) . ' a ' . ucwords($request->resource);
                    $permission = new Permission();
                    $permission->resource = strtolower($request->resource);
                    $permission->name = $slug;
                    $permission->display_name = $display_name;
                    $permission->description = $description;
                    $permission->save();
                }

                //user activity
                $action = "Create CRUD";
                $object = "Permission";
                $object_id = null;
                $value_before = null;
                $value_after = "Resource:" . $request->resource;

                UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
                //user activity
                return redirect()->route('acl.permissions.index')->with([
                    'status_type' => 'success',
                    'status' => 'Permissions has been successfully added'
                ]);
            }
        } else {
            return redirect()->back()->withInput()->with([
                'status_type' => 'error',
                'status' => 'Some thing wrong please contact to admin for support'
            ]);
        }
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
        //
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
        Creeper::canOrFail('update-permissions');

        $permission = Permission::find($id);

        if (isset($permission)) {
            return view('acl::Permissions.edit', compact('permission'));
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
        Creeper::canOrFail('update-permissions');

        $this->validate($request, [
            'display_name' => 'required|max:255',
            'description' => 'sometimes|max:255'
        ]);
        $permission = Permission::findOrFail($id);
        $permission->display_name = $request->display_name;
        $permission->description = $request->description;
        $permission->save();

        //user activity
        $action = "Edit";
        $object = "Permission";
        $object_id = $request->id;
        $value_before = "Display_Name:" . $permission->display_name
            . "," . "Description:" . $permission->description;

        $value_after = "Display_Name:" . $request->display_name .
            "," .
            "Description:" . $request->description;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity
        return redirect()->route('acl.permissions.index')->with([
            'status_type' => 'success',
            'status' => 'Successfully update the ' . $permission->display_name . ' permission in the database.'
        ]);
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
        Creeper::canOrFail('delete-permissions');

        $permission = Permission::findOrFail($id);
        if ($permission) {
            // Force Delete
            $permission->admins()->sync([]); // Delete relationship data
            $permission->roles()->sync([]); // Delete relationship data

            $permission->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete
            //user activity
            $action = "Delete";
            $object = "Permission";
            $object_id = $id;
            $value_before = "Display_Name:" . $permission->display_name
                . "," . "Description:" . $permission->description;
            $value_after = null;
            UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
            //end user activity
            return redirect()->back()->with([
                'status_type' => 'success',
                'status' => 'Permission has been successfully deleted'
            ]);
        }
        $url = URL::previous();
        return $this->notFound($url);

    }

    public function deleteSelected(Request $request)
    {

        Creeper::canOrFail('delete-permissions');

        $ids = $request->data;
        foreach ($ids as $id) {
            $permission = Permission::findOrFail($id);
            if ($permission) {
                // Force Delete
                $permission->admins()->sync([]); // Delete relationship data
                $permission->roles()->sync([]); // Delete relationship data

                $permission->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Permissions has been successfully deleted');
        return response()->json(['status' => 'Permissions has been successfully deleted'], 200);
    }


    /**
     * Server side get all permissions
     * @return [array] [permissons]
     */
    public function getData()
    {

        Creeper::canOrFail('browse-permissions');

        $model = Permission::orderBy('resource', 'asc')->get();

        return DataTables::collection($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->addColumn('action', function ($model) {
                return '<a class="btn btn-circle btn-success edit" href="' . route('acl.permissions.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                            <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action'])
            ->make(true);
    }
}
