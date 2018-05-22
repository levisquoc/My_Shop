<?php

namespace App\Modules\Acl\Http\Controllers;

use App\Facade\Creeper;
use App\Modules\Acl\Models\Role;
use App\Modules\Posts\Models\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Acl\Models\Admin;
use DataTables;
use Auth;
use Illuminate\Support\Facades\Session;
use URL;
use App\Helpers\ErrorHelpers;
use App\Helpers\UserActivityHelpers;

class AdminController extends Controller
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
        Creeper::canOrFail('browse-admins');

        return view('acl::Admins.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-admins');

        $roles = Role::get();
        $categories = Category::all();
        if (!Auth::guard('admin')->user()->hasRole('superadministrator')) {
            $roles = $roles->when('name', '!=', 'superadministrator');
        }

        return view('acl::Admins.create', compact('roles', 'categories'));

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
        Creeper::canOrFail('create-admins');

        $this->validate($request, [
            'name' => 'required|unique:admins,name|alpha_dash',
            'email' => 'required|email|unique:admins,email',
            'gender' => 'nullable|alpha',
            'password' => 'required|confirmed|min:6',
            'role' => 'required',
            'phone' => 'nullable|numeric',
            'secret_password' => 'required|different:password|min:6',
        ]);

        $data = $request->all();
        //dd($data);

        //user activity
        $action = "Create";
        $object = "Admin";
        $object_id = null;
        $value_before = null;
        $value_after = "Name:" . $request->name .
            "," .
            "Email:" . $request->email .
            "," .
            "Password:" . $request->Password .
            "," .
            "Phone:" . $request->phone .
            "," .
            "Role:" . $request->role;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);

        $admin = new Admin();
        $admin->name = $data['name'];
        $admin->email = $data['email'];
        $admin->gender = $data['gender'];
        $admin->password = bcrypt($data['password']);
        $admin->phone = $data['phone'];
        $admin->avatar = $data['avatar'];
        $admin->secret_password = bcrypt($data['secret_password']);
        $admin->save();

        if (isset($admin)) {
            $admin->roles()->sync([$data['role']]);

            $admin->categories()->sync($request->cate_id);

            return redirect()->route('acl.admins.index')->with([
                'status_type' => 'success',
                'status' => $admin->name . ' has been successfully added'
            ]);
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
        Creeper::canOrFail('read-admins');

        $admin = Admin::findOrFail($id);

        if (isset($admin)) {
            $admin = view('acl::Admins.read', compact('admin'))->render();
            return response()->json($admin, 200);
        }

        return response(404);

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
        Creeper::canOrFail('update-admins');

        $admin = Admin::findOrFail($id);
        $roles = Role::get();
        $categories = Category::all();
        if (!Auth::guard('admin')->user()->hasRole('superadministrator')) {
            $roles = $roles->when('name', '!=', 'superadministrator');
        }


        if (isset($admin)) {
            return view('acl::Admins.edit', compact('admin', 'roles', 'categories'));
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
        Creeper::canOrFail('update-admins');

        $rule = [
            'email' => 'required|email|unique:admins,email,' . $id,
            'gender' => 'nullable|alpha',
            'role' => 'required',
            'phone' => 'nullable|numeric',
        ];

        if (isset($request->password)) {
            $rule['password'] = 'required|confirmed|min:6';
        }
        if (isset($request->secret_password) && isset($request->password)) {
            $rule['secret_password'] = 'required|different:password|min:6';
        }
        if (isset($request->secret_password)) {
            $rule['secret_password'] = 'required|min:6';
        }

        $this->validate($request, $rule);

        $data = $request->all();

        $admin = Admin::findOrFail($id);
        if (isset($admin)) {
            $admin->email = $data['email'];
            $admin->gender = $data['gender'];
            if (isset($request->password)) {
                $admin->password = bcrypt($data['password']);
            }
            $admin->phone = $data['phone'];
            $admin->avatar = $data['avatar'];
            if (isset($request->secret_password)) {
                $admin->secret_password = bcrypt($data['secret_password']);
            }

            //user activity
            $action = "Edit";
            $object = "Admin";
            $object_id = $id;
            $value_before = "Name:" . $admin->name .
                "," .
                "Email:" . $admin->email .
                "," .
                "Password:" . $admin->Password .
                "," .
                "Phone:" . $admin->phone .
                "," .
                "Role:" . $admin->role;

            $value_after = "Name:" . $request->name .
                "," .
                "Email:" . $request->email .
                "," .
                "Password:" . $request->Password .
                "," .
                "Phone:" . $request->phone .
                "," .
                "Role:" . $request->role;

            UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
            //end user activity

            $admin->save();

            $admin->categories()->sync($request->cate_id);

            $admin->roles()->sync([$data['role']]);

            return redirect()->route('acl.admins.index')->with([
                'status_type' => 'success',
                'status' => $admin->name . ' has been successfully updated'
            ]);
        } else {
            $url = URL::previous();
            return $this->notFound($url);
        }

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
        Creeper::canOrFail('delete-admins');

        $admin = Admin::findOrFail($id);
        if ($admin) {
            // Force Delete
            $admin->roles()->sync([]); // Delete relationship data
            $admin->permissions()->sync([]); // Delete relationship data

            $admin->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete
            //user activity
            $action = "Delete";
            $object = "Admin";
            $object_id = $id;
            $value_before = "Name:" . $admin->name .
                "," .
                "Email:" . $admin->email .
                "," .
                "Password:" . $admin->password .
                "," .
                "Phone:" . $admin->phone .
                "," .
                "Role:" . $admin->role;
            $value_after = null;
            UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
            //end user activity

            return redirect()->back()->with([
                'status_type' => 'success',
                'status' => 'Admin has been successfully deleted'
            ]);
        }
        $url = URL::previous();
        return $this->notFound($url);
    }

    /**
     * Remove select item
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteSelected(Request $request)
    {

        Creeper::canOrFail('delete-admins');

        $ids = $request->data;
        foreach ($ids as $id) {
            $admin = Admin::findOrFail($id);

            if (isset($admin)) {
                // Force Delete
                $admin->roles()->sync([]); // Delete relationship data
                $admin->permissions()->sync([]); // Delete relationship data

                $admin->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Admins has been successfully deleted');
        return response()->json(['status' => 'Admins has been successfully deleted'], 200);
    }

    /**
     * Severside
     *
     * @return mixed
     */
    public function getData()
    {
        Creeper::canOrFail('browse-admins');
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
                if (Auth::guard('admin')->user()->id != $model->id) {
                    return '<button class="btn btn-circle btn-info read" type="button" data-toggle="tooltip" data-original-title="View" data-target="' . route('acl.admins.show',
                            $model->id) . '" onclick="read(this)"> <i class="fa fa-eye"></i> </button>
                            <a class="btn btn-circle btn-success edit" href="' . route('acl.admins.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                            <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
                } else {
                    return '<button class="btn btn-circle btn-info read" type="button" data-toggle="tooltip" data-original-title="View" data-target="' . route('acl.admins.show',
                            $model->id) . '" onclick="read(this)"> <i class="fa fa-eye"></i> </button>
                            <a class="btn btn-circle btn-success edit" href="' . route('acl.admins.edit',
                            $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>';
                }
            })
            ->rawColumns(['check', 'action'])
            ->make(true);
    }

    public function profile($name)
    {

        $admin = Admin::where('name', $name)->first();

        if (isset($admin)) {
            return view('acl::Admins.profile', compact('admin'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }
}
