<?php

namespace App\Modules\Contacts\Http\Controllers;

use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use App\Facade\Creeper;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Modules\Contacts\Models\Contact;
use Illuminate\Support\Facades\Session;
use App\Helpers\ErrorHelpers;
use App\Translator;
use DataTables;
use Auth, URL;


class ContactController extends Controller
{
    use ErrorHelpers, Translator;

    protected $modelTarget;

    public function __construct(Contact $model)
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
        Creeper::canOrFail('browse-contacts');
        return view('contacts::index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
        Creeper::canOrFail('read-contacts');

        $contact = contact::findOrFail($id);

        if (isset($contact)) {
            return view('contacts::read', compact('contact'));
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
        Creeper::canOrFail('delete-contacts');

        $Contact = Contact::findOrFail($id);
        if ($Contact) {
            // Force Delete
            $Contact->delete();

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

    public function getSlug(Request $request)
    {

    }

    public function getData()
    {
        Creeper::canOrFail('browse-contacts');

        $model = Contact::query();

        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->addColumn('action', function ($model) {
                return '<a href="' . route('contacts.show', $model->id) . '" class="btn btn-circle btn-info read"> <i class="fa fa-eye"></i> </a>
                            <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action'])
            ->make(true);
    }
}
