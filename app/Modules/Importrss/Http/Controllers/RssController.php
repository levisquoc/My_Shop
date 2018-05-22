<?php

namespace App\Modules\Importrss\Http\Controllers;

use App\Facade\Creeper;
use App\Helpers\ErrorHelpers;
use App\Helpers\RSSHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Cviebrock\EloquentSluggable\Services\SlugService;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Modules\Importrss\Http\Requests\CreateRssRequest;
use App\Modules\Importrss\Http\Requests\UpdateRssRequest;

use App\Modules\Importrss\Models\rss_links;
use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Post;
use App\Helpers\UserActivityHelpers;

use DataTables;
use Auth, URL;

use Sunra\PhpSimple\HtmlDomParser;


class RssController extends Controller
{
    use ErrorHelpers;
    use RSSHelpers;
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
        Creeper::canOrFail('browse-rss');

        return view('importrss::RSS.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-rss');

        $categories = Category::GetWithStatus('publish');

        return view('importrss::RSS.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRssRequest $request)
    {
        Creeper::canOrFail('create-rss');
        $data = $request->all();

        //user activity
        $action = "Create";
        $object = "Rss";
        $object_id = null;
        $value_before = null;
        $value_after = "Data:" . $data;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);

        //user activity

        rss_links::create($data);
        return redirect()->route('rss.rss.index')->with([
            'status_type' => 'success',
            'status' => $request->page_target . ' has been successfully created'
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
        $rss = rss_links::find($id);
        //load link rss
        if ($rss->page_target == '24h') {
            return $this->show_post_twentyFourHour($rss);
        } elseif ($rss->page_target == 'afamily.vn') {
            return $this->show_post_afamily($rss);
        } elseif ($rss->page_target == 'VNEXPRESS') {
            return $this->show_post_vnexpress($rss);
        } elseif ($rss->page_target == 'dantri') {
            return $this->show_post($rss);
        }
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
        Creeper::canOrFail('update-rss');
        $pages_target = config('creeper.page_target');
        $item = rss_links::find($id);
        $categories = Category::GetWithStatus('publish');
        if (isset($item)) {
            return view('importrss::RSS.edit', compact('item', 'pages_target', 'categories'));
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
    public function update(UpdateRssRequest $request, $id)
    {
        Creeper::canOrFail('update-rss');
        $item = rss_links::findOrFail($id);
        $item->rss_link = $request->rss_link;
        $item->page_target = $request->page_target;
        $item->cate_id = $request->cate_id;

        //user activity
        $action = "Edit";
        $object = "Rss";
        $object_id = $category->id;
        $value_before = "Name:" . $item;

        $value_after = "Rss_link:" . $request->rss_link .
            "," .
            "Page_target:" . $request->page_target .
            "," .
            "Cate_id:" . $request->cate_id;

        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        $item->save();
        return redirect()->route('rss.rss.index')->with([
            'status_type' => 'success',
            'status' => 'Successfully update the ' . $request->link_rss . ' permission in the database.'
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
        Creeper::canOrFail('delete-rss');

        $item = rss_links::findOrFail($id);
        if ($item) {
            // Force Delete
            //user activity
            $action = "Delete";
            $object = "Rss";
            $object_id = $id;
            $value_before = "Name:" . $item;
            $value_after = null;
            UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
            //end user activity
            $item->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            return redirect()->back()->with([
                'status_type' => 'success',
                'status' => 'Link has been successfully deleted'
            ]);
        }
        $url = URL::previous();
        return $this->notFound($url);

    }


    /**
     * Server side get all permissions
     * @return [array] [permissons]
     */
    public function getData()
    {

        Creeper::canOrFail('browse-rss');

        $model = rss_links::with('category');

        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->addColumn('action', function ($model) {
                return '<a class="btn btn-circle btn-primary download" href="' . route('rss.rss.show', $model->id) . '" data-toggle="tooltip" data-original-title="Download"> <i class="fa fa-download"></i> </a>
                        <a class="btn btn-circle btn-success edit" href="' . route('rss.rss.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                           <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action'])
            ->make(true);
    }

    public function download(Request $request)
    {
        Creeper::canOrFail('download-rss');
        $rss = rss_links::find($request->rss_id);

        if ($rss->page_target == '24h') {
            return $this->twentyFourHour($request, $rss);
        } elseif ($rss->page_target == 'afamily.vn') {
            return $this->afamily($request, $rss);
        } elseif ($rss->page_target == 'VNEXPRESS') {
            return $this->vnexpress($request, $rss);
        } elseif ($rss->page_target == 'dantri') {
            return $this->dantri($request, $rss);
        }


    }
}
