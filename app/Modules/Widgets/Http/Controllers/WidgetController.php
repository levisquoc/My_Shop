<?php

namespace App\Modules\Widgets\Http\Controllers;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use App\Facade\Creeper;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Widgets\Models\Widget;
use Illuminate\Support\Facades\Session;
use App\Helpers\ErrorHelpers;
use App\Translator;
use DataTables;
use Auth, URL;


class WidgetController extends Controller
{
    use ErrorHelpers, Translator;

    protected $modelTarget;

    public function __construct(Widget $model)
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
        Creeper::canOrFail('browse-widgets');
        return view('widgets::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-widgets');
        return view('widgets::create');
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
        $this->validate($request, [
            'name' => 'required|unique:widgets'
        ]);
        Creeper::canOrFail('create-widgets');

        $data = $request->all();
        $data['slug'] = '[' . $data['slug'] . ']';
        $this->saveCreate($data);
        return redirect()->route('widgets.index')->with([
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
        Creeper::canOrFail('read-widgets');

        $widget = widget::findOrFail($id);

        return view('widgets::read', compact('widget'));
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

        if ($item = widget::findOrFail($id)) {
            $item = $this->dataLanguage($item);
            return view('widgets::edit', compact('item'));
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
        Creeper::canOrFail('update-widgets');
        $data = $request->all();
        $this->saveEdit($id, $data);

        return redirect()->route('widgets.index')->with([
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
        Creeper::canOrFail('delete-widgets');

        $widget = widget::findOrFail($id);
        if ($widget) {
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

        Creeper::canOrFail('delete-widgets');

        $ids = $request->data;
        foreach ($ids as $id) {
            $widget = widget::findOrFail($id);

            if (isset($widget)) {
                // Force Delete

                $widget->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

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
            $slug = SlugService::createSlug(widget::class, 'slug', $request->slug, ['unique' => false]);
        } else {
            $slug = SlugService::createSlug(widget::class, 'slug', $request->slug);
        }

        return $slug;

    }

    public function getData()
    {
        Creeper::canOrFail('browse-widgets');

        $model = Widget::query();

        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->addColumn('action', function ($model) {
                return '<a href="' . route('widgets.show', $model->id) . '" class="btn btn-circle btn-info read"> <i class="fa fa-eye"></i> </a>
                            <a class="btn btn-circle btn-success edit" href="' . route('widgets.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                            <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action'])
            ->make(true);
    }
}
