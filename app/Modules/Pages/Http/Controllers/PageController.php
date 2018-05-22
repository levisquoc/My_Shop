<?php

namespace App\Modules\Pages\Http\Controllers;

use App\Facade\Creeper;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use App\Modules\Pages\Http\Requests\PageRequest;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Page;
use DataTables;
use Auth, URL;
use Illuminate\Support\Facades\Session;
use App\Helpers\ErrorHelpers;
use App\Translator;
use App\Helpers\UserActivityHelpers;

class PageController extends Controller
{

    use ErrorHelpers, Translator;

    protected $modelTarget;

    public function __construct(Page $model)
    {
        $this->middleware('admin');
        $this->modelTarget = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Creeper::canOrFail('browse-pages');
        return view('pages::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-pages');
        return view('pages::create');
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
        Creeper::canOrFail('create-pages');

        $data = $request->all();

        //user activity
        $action = "Create";
        $object = "Pages";
        $object_id = null;
        $value_before = null;
        $value_after = "Title:" . $request->title .
            "," .
            "Slug:" . $request->slug .
            "," .
            "Content:" . $request->content .
            "," .
            "Status:" . $request->status .
            "," .
            "Description:" . $request->description .
            "," .
            "Pos:" . $request->pos .
            "," .
            "Image: Create Image" .
            "," .
            "Seo_title:" . $request->seo_title .
            "," .
            "Seo_keyword:" . $request->seo_keyword .
            "," .
            "Seo_description:" . $request->seo_description;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //user activity

        $this->saveCreate($data);
        return redirect()->route('pages.index')->with([
            'status_type' => 'success',
            'status' => $request->title . ' has been successfully created'
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
        Creeper::canOrFail('read-pages');

        $page = Page::findOrFail($id);

        // if (isset($page)){
        //     $page = view('pages::read',compact('page'))->render();
        //     return response()->json($page, 200);
        // }

        // return response(404);
        return view('pages::read', compact('page'));
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

        if ($item = Page::findOrFail($id)) {
            $item = $this->dataLanguage($item);
            return view('pages::edit', compact('item'));
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
        Creeper::canOrFail('update-pages');
        $data = $request->all();
        $this->saveEdit($id, $data);
        $page = Page::find($id);

        //user activity
        $action = "Edit";
        $object = "Pages";
        $object_id = $page->id;
        $value_before = "Title:" . $page->title .
            "," .
            "Slug:" . $page->slug .
            "," .
            "Content:" . $page->content .
            "," .
            "Status:" . $page->status .
            "," .
            "Description:" . $page->description .
            "," .
            "Pos:" . $page->pos .
            "," .
            "Image: Edit Image" .
            "," .
            "Seo_title:" . $page->seo_title .
            "," .
            "Seo_keyword:" . $page->seo_keyword .
            "," .
            "Seo_description:" . $page->seo_description;

        $value_after = "Title:" . $request->title .
            "," .
            "Slug:" . $request->slug .
            "," .
            "Content:" . $request->content .
            "," .
            "Status:" . $request->status .
            "," .
            "Description:" . $request->description .
            "," .
            "Pos:" . $request->pos .
            "," .
            "Image: Create Image" .
            "," .
            "Seo_title:" . $request->seo_title .
            "," .
            "Seo_keyword:" . $request->seo_keyword .
            "," .
            "Seo_description:" . $request->seo_description;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        return redirect()->route('pages.index')->with([
            'status_type' => 'success',
            'status' => $request->title . ' has been successfully updated'
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
        Creeper::canOrFail('delete-pages');

        $page = Page::findOrFail($id);

        //user activity
        $action = "Delete";
        $object = "Pages";
        $object_id = $page->id;
        $value_before = "Title:" . $page->title .
            "," .
            "Slug:" . $page->slug .
            "," .
            "Content:" . $page->content .
            "," .
            "Status:" . $page->status .
            "," .
            "Description:" . $page->description .
            "," .
            "Pos:" . $page->pos .
            "," .
            "Image: Edit Image" .
            "," .
            "Seo_title:" . $page->seo_title .
            "," .
            "Seo_keyword:" . $page->seo_keyword .
            "," .
            "Seo_description:" . $page->seo_description;

        $value_after = null;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        if ($page) {
            // Force Delete
            $this->deleteData($id);

            return redirect()->back()->with([
                'status_type' => 'success',
                'status' => 'Data has been successfully deleted'
            ]);
        }
        $url = URL::previous();
        return $this->notFound($url);
    }

    public function deleteSelected(Request $request)
    {

        Creeper::canOrFail('delete-pages');

        $ids = $request->data;
        foreach ($ids as $id) {
            $page = Page::findOrFail($id);

            if (isset($page)) {
                // Force Delete

                $page->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Data has been successfully deleted');
        return response()->json(['status' => 'Data has been successfully deleted'], 200);
    }

    public function getSlug(Request $request)
    {

        if ($request->type) {
            $slug = SlugService::createSlug(Page::class, 'slug', $request->slug, ['unique' => false]);
        } else {
            $slug = SlugService::createSlug(Page::class, 'slug', $request->slug);
        }

        return $slug;

    }

    public function getData()
    {
        Creeper::canOrFail('browse-pages');

        $model = Page::query();
        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->editColumn('image', function ($model) {
                return '<img src="' . $model->image . '" id="thumbnail" style="width:100%;" />';
            })
            ->addColumn('action', function ($model) {
                return '<a href="' . route('pages.show', $model->id) . '" class="btn btn-circle btn-info read"> <i class="fa fa-eye"></i> </a>
                            <a class="btn btn-circle btn-success edit" href="' . route('pages.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                            <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action', 'image'])
            ->make(true);
    }
}
