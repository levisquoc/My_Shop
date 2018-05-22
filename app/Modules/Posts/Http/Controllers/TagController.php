<?php

namespace App\Modules\Posts\Http\Controllers;

use App\Facade\Creeper;
use App\Helpers\UserActivityHelpers;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Modules\Posts\Http\Requests\CreateTagRequest;
use App\Modules\Posts\Http\Requests\UpdateTagRequest;
use App\Modules\Acl\Models\Role;
use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Tag;
use App\Modules\Posts\Models\Post;
use DataTables;
use Auth, URL;
use Illuminate\Support\Facades\Session;
use App\Helpers\ErrorHelpers;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Creeper::canOrFail('browse-tags');
        $trashed = Tag::onlyTrashed()->get();

        return view('posts::Tags.index', compact('trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-tags');

        return view('posts::Tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateTagRequest $request)
    {
        Creeper::canOrFail('create-tags');

        //user activity
        $action = "Create";
        $object = "Tags";
        $object_id = null;
        $value_before = null;
        $value_after = "Name:" . $request->name .
            "," .
            "Slug:" . $request->slug .
            "," .
            "Pos:" . $request->pos .
            "," .
            "Status:" . $request->status;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);

        $data['name'] = $request->name;
        $data['slug'] = $request->slug;
        $data['status'] = $request->status;
        $data['pos'] = $request->pos;
        Tag::create($data);
        return redirect()->route('blog.tags.index')->with([
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
        Creeper::canOrFail('update-tags');

        $item = Tag::findOrFail($id);

        if (isset($item)) {
            return view('posts::Tags.edit', compact('item'));
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
    public function update(UpdateTagRequest $request, $id)
    {
        Creeper::canOrFail('update-tags');

        $tag = Tag::find($id);

        //user activity
        $action = "Edit";
        $object = "Tags";
        $object_id = $tag->id;
        $value_before = "Name:" . $tag->name .
            "," .
            "Slug:" . $tag->slug .
            "," .
            "Pos:" . $tag->pos .
            "," .
            "Status:" . $tag->status;

        $value_after = "Name:" . $request->name .
            "," .
            "Slug:" . $request->slug .
            "," .
            "Pos:" . $request->pos .
            "," .
            "Status:" . $request->status;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        $tag->name = $request->name;
        $tag->slug = $request->slug;
        $tag->status = $request->status;
        $tag->pos = $request->pos;
        $tag->save();
        return redirect()->route('blog.tags.index')->with([
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
        Creeper::canOrFail('delete-tags');

        $tag = Tag::findOrFail($id);


        //user activity
        $action = "Destroy";
        $object = "Tags";
        $object_id = $tag->id;
        $value_before = "Name:" . $tag->name .
            "," .
            "Slug:" . $tag->slug .
            "," .
            "Pos:" . $tag->pos .
            "," .
            "Status:" . $tag->status;

        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity


        if (isset($tag)) {
            // Force Delete

            $tag->Delete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            return redirect()->back()->with([
                'status_type' => 'success',
                'status' => 'Tag has been successfully deleted'
            ]);
        }
        $url = URL::previous();
        return $this->notFound($url);
    }

    public function deleteSelected(Request $request)
    {
        Creeper::canOrFail('delete-tags');
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
        Session::flash('status', 'Tags has been successfully deleted');
        return response()->json(['status' => 'Tags has been successfully deleted'], 200);
    }

    public function getSlug(Request $request)
    {

        if ($request->type) {
            $slug = SlugService::createSlug(Tag::class, 'slug', $request->slug, ['unique' => false]);
        } else {
            $slug = SlugService::createSlug(Tag::class, 'slug', $request->slug);
        }

        return $slug;

    }

    public function getData()
    {
        Creeper::canOrFail('browse-tags');
        $model = Tag::query();
        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->addColumn('action', function ($model) {
                return '<a class="btn btn-circle btn-success edit" href="' . route('blog.tags.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
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

        Creeper::canOrFail('delete-tags');

        $restoreTrash = Tag::withTrashed()
            ->where('id', $id)
            ->restore();

        //user activity
        $action = "Restore";
        $object = "Tags";
        $object_id = $id;
        $value_before = null;
        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        return redirect()
            ->back()
            ->with(['status_type' => 'success', 'status' => 'Tag has been successfully restore']);

    }

    //call when remove item form trash table
    public function remove($id)
    {

        Creeper::canOrFail('delete-tags');

        $removeTrash = Tag::withTrashed()
            ->where('id', $id)
            ->forceDelete();

        //user activity
        $action = "Remove";
        $object = "Tags";
        $object_id = $id;
        $value_before = null;
        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        return redirect()
            ->back()
            ->with(['status_type' => 'success', 'status' => 'Tag has been successfully remove']);
    }

    public function deleteTrashSelected(Request $request)
    {

        Creeper::canOrFail('delete-tags');

        $ids = $request->data;

        foreach ($ids as $id) {
            $removeTrash = Tag::withTrashed()->where('id', $id)->first();

            if (isset($removeTrash)) {
                // Force Delete

                $removeTrash->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Tags has been successfully deleted');
        return response()->json(['status' => 'Tags has been successfully deleted'], 200);
    }

    public function restoreTrashSelected(Request $request)
    {

        Creeper::canOrFail('delete-tags');

        $ids = $request->data;

        foreach ($ids as $id) {
            $restoreTrash = Tag::withTrashed()->where('id', $id)->first();

            if (isset($restoreTrash)) {
                // Force Delete

                $restoreTrash->restore(); // Now force delete will work regardless of whether the pivot table has cascading restore

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Tags has been successfully restored');
        return response()->json(['status' => 'Tags has been successfully restored'], 200);
    }
}
