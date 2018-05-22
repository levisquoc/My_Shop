<?php

namespace App\Modules\Posts\Http\Controllers;

use App\Facade\Creeper;
use \Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Modules\Posts\Http\Requests\CreateCategoryRequest;
use App\Modules\Posts\Http\Requests\UpdateCategoryRequest;
use App\Modules\Acl\Models\Role;
use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Category;
use DataTables;
use Auth, URL;
use Illuminate\Support\Facades\Session;
use App\Helpers\ErrorHelpers;
use App\Helpers\UserActivityHelpers;
use App\Modules\Userlogs\Models\UserActivity;

class CategoryController extends Controller
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
    public function index(Request $request)
    {
        Creeper::canOrFail('browse-categories');

        $trashed = Category::onlyTrashed()->get();
        //dd($trashed);
        return view('posts::Categories.index', compact('trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-categories');

        $data['categories'] = Category::all();
        return view('posts::Categories.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        Creeper::canOrFail('create-categories');

        $data = $request->all();

        //user activity
        $action = "Create";
        $object = "Category";
        $object_id = null;
        $value_before = null;
        $value_after = "Name:" . $request->name .
            "," .
            "Slug:" . $request->slug .
            "," .
            "Parent:" . $request->parent_id .
            "," .
            "Status:" . $request->status;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        Category::create($data);
        //user activity

        return redirect()->route('blog.categories.index')->with([
            'status_type' => 'success',
            'status' => $request->name . ' has been successfully created'
        ]);
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
        Creeper::canOrFail('read-categories');

        $cate = Category::findOrFail($id);

        if (isset($cate)) {
            $cate = view('posts::Categories.read', compact('cate'))->render();
            return response()->json($cate, 200);
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
        Creeper::canOrFail('update-categories');

        $item = Category::findOrFail($id);
        $categories = Category::all();


        if (isset($item)) {
            return view('posts::Categories.edit', compact('item', 'categories'));
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
    public function update(UpdateCategoryRequest $request, $id)
    {
        Creeper::canOrFail('update-categories');

        $category = Category::find($id);

        //user activity
        $action = "Edit";
        $object = "Category";
        $object_id = $category->id;
        $value_before = "Name:" . $category->name .
            "," .
            "Slug:" . $category->slug .
            "," .
            "Parent:" . $category->parent_id .
            "," .
            "Status:" . $category->status;

        $value_after = "Name:" . $request->name .
            "," .
            "Slug:" . $request->slug .
            "," .
            "Parent:" . $request->parent_id .
            "," .
            "Status:" . $request->status;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        $category->name = $request->name;
        $category->slug = $request->slug;
        $category->parent_id = $request->parent_id;
        $category->status = $request->status;


        $category->save();
        return redirect()->route('blog.categories.index')->with([
            'status_type' => 'success',
            'status' => $request->name . ' has been successfully updated'
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
        Creeper::canOrFail('delete-categories');

        $category = Category::findOrFail($id);

        //user activity
        $action = "Delete";
        $object = "Category";
        $object_id = $category->id;
        $value_before = "Name:" . $category->name .
            "," .
            "Slug:" . $category->slug .
            "," .
            "Parent:" . $category->parent_id .
            "," .
            "Status:" . $category->status;
        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        if ($category) {
            // Force Delete

            $category->Delete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            return redirect()->back()->with([
                'status_type' => 'success',
                'status' => 'Category has been successfully deleted'
            ]);
        }
        $url = URL::previous();
        return $this->notFound($url);
    }

    public function deleteSelected(Request $request)
    {

        Creeper::canOrFail('delete-categories');

        $ids = $request->data;
        foreach ($ids as $id) {
            $category = Category::findOrFail($id);

            if (isset($category)) {
                // Force Delete

                $category->Delete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Categories has been successfully deleted');
        return response()->json(['status' => 'Categories has been successfully deleted'], 200);
    }

    public function getSlug(Request $request)
    {

        if ($request->type) {
            $slug = SlugService::createSlug(Category::class, 'slug', $request->slug, ['unique' => false]);
        } else {
            $slug = SlugService::createSlug(Category::class, 'slug', $request->slug);
        }

        return $slug;

    }

    public function getData()
    {
        Creeper::canOrFail('browse-categories');

        $model = Category::query()->with('parent');

        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->addColumn('action', function ($model) {
                return '<button class="btn btn-circle btn-info read" type="button" data-toggle="tooltip" data-original-title="View" data-target="' . route('blog.categories.show',
                        $model->id) . '" onclick="read(this)"> <i class="fa fa-eye"></i> </button>
                            <a class="btn btn-circle btn-success edit" href="' . route('blog.categories.edit',
                        $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                            <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action'])
            ->make(true);
    }

    /*******************
     * Trash Functions *
     *******************/

    //call when restore trash
    public function restore($id)
    {

        Creeper::canOrFail('delete-categories');

        //user activity
        $action = "Restore";
        $object = "Category";
        $object_id = $id;
        $value_before = null;
        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        $restoreTrash = Category::withTrashed()
            ->where('id', $id)
            ->restore();


        return redirect()
            ->back()
            ->with(['status_type' => 'success', 'status' => 'Category has been successfully restore']);

    }

    //call when remove item form trash table
    public function remove($id)
    {

        Creeper::canOrFail('delete-categories');

        //user activity
        $action = "Remove";
        $object = "Category";
        $object_id = $id;
        $value_before = null;
        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        $removeTrash = Category::withTrashed()
            ->where('id', $id)
            ->forceDelete();

        return redirect()
            ->back()
            ->with(['status_type' => 'success', 'status' => 'Category has been successfully remove']);
    }

    public function deleteTrashSelected(Request $request)
    {

        Creeper::canOrFail('delete-categories');

        $ids = $request->data;

        foreach ($ids as $id) {
            $removeTrash = Category::withTrashed()->where('id', $id)->first();

            if (isset($removeTrash)) {
                // Force Delete

                $removeTrash->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Categories has been successfully deleted');
        return response()->json(['status' => 'Categories has been successfully deleted'], 200);
    }

    public function restoreTrashSelected(Request $request)
    {

        Creeper::canOrFail('delete-categories');

        $ids = $request->data;

        foreach ($ids as $id) {
            $restoreTrash = Category::withTrashed()->where('id', $id)->first();

            if (isset($restoreTrash)) {
                // Force Delete

                $restoreTrash->restore(); // Now force delete will work regardless of whether the pivot table has cascading restore

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Categories has been successfully restored');
        return response()->json(['status' => 'Categories has been successfully restored'], 200);
    }
}
