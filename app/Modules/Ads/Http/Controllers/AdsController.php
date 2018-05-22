<?php

namespace App\Modules\Ads\Http\Controllers;

use Illuminate\Http\Request;
use App\Facade\Creeper;

use App\Http\Requests;
use App\Modules\Ads\Http\Requests\AdvertRequest;
use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Category;

use App\Modules\Ads\Models\Ads;

use App\Helpers\ErrorHelpers;
use App\Translator;
use DataTables;
use URL;

class AdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('ads::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-ads');
        $categories = Category::all();
        return view('ads::create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(AdvertRequest $request)
    {
        Creeper::canOrFail('create-ads');
        $data['title'] = $request->title;
        $data['link'] = $request->link;
        $data['image'] = $request->image;
        if ($request->cate == null) {
            $data['position'] = $request->position;
        } else {
            $data['position'] = $request->position . $request->cate;
        }
        $data['position'] = $request->position;
        $data['publish_date'] = $request->publish_date;
        $data['expiration_date'] = $request->expiration_date;
        $data['status'] = $request->status;
        Ads::create($data);
        return redirect()->route('ads.index')->with([
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
        Creeper::canOrFail('update-ads');

        $item = Ads::findOrFail($id);
        if (isset($item)) {
            return view('ads::edit', compact('item'));
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
    public function update(AdvertRequest $request, $id)
    {
        Creeper::canOrFail('update-ads');

        $ads = Ads::find($id);
        $ads->title = $request->title;
        $ads->link = $request->link;
        $ads->image = $request->image;
        $ads->position = $request->position;
        $ads->publish_date = $request->publish_date;
        $ads->expiration_date = $request->expiration_date;
        $ads->status = $request->status;
        $ads->save();
        return redirect()->route('ads.index')->with([
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
        Creeper::canOrFail('delete-ads');

        $Ads = Ads::findOrFail($id);
        if ($Ads) {
            // Force Delete
            $Ads->delete();

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

        Creeper::canOrFail('delete-contacts');

        $ids = $request->data;
        foreach ($ids as $id) {
            $Contact = Contact::findOrFail($id);

            if (isset($Contact)) {
                // Force Delete

                $Contact->delete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Data has been successfully deleted');
        return response()->json(['status' => 'Data has been successfully deleted'], 200);
    }

    public function getData()
    {
        Creeper::canOrFail('browse-ads');

        $model = Ads::query();

        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->editColumn('image', function ($model) {
                return '<img src="' . $model->image . '" id="thumbnail" style="width:100%;" />';
            })
            ->addColumn('action', function ($model) {
                return '<a class="btn btn-circle btn-success edit" href="' . route('ads.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                            <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action', 'image'])
            ->make(true);
    }
}
