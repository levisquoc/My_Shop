<?php

namespace App\Modules\Posts\Http\Controllers;

use App\Facade\Creeper;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Modules\Posts\Http\Requests\CreatePostRequest;
use App\Modules\Posts\Http\Requests\UpdatePostRequest;
use App\Http\Controllers\Controller;
use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Post;
use App\Modules\Posts\Models\Tag;
use DataTables;
use Auth, URL;
use Illuminate\Support\Facades\Session;
use App\Helpers\ErrorHelpers;
use App\Helpers\UserActivityHelpers;
use App\Modules\Userlogs\Models\UserActivity;

class PostController extends Controller
{

    use ErrorHelpers;

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
        Creeper::canOrFail('browse-posts');
        $trashed = Post::onlyTrashed()->get();


        return view('posts::Posts.index', compact('trashed'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        Creeper::canOrFail('create-posts');

        $data['categories'] = Category::all();
        $data['tags'] = Tag::where('status', 'publish')->orderBy('created_at', 'desc')->limit(8)->get();
        return view('posts::Posts.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        Creeper::canOrFail('create-posts');

        $data = $request->all();
        //user activity
        $action = "Create";
        $object = "Posts";
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
            "Seo_description:" . $request->seo_description .
            "," .
            "Category_id:" . $request->category_id .
            "," .
            "Author_id:" . $request->author_id;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);

        $item = Post::create($data);
        if ($request->has('tags')) {
            $tags = [];

            foreach ($request->tags as $tag) {
                $tag_id = Tag::where('id', $tag)->first();
                if (isset($tag_id)) {
                    $tags[] = $tag_id->id;
                } else {
                    $data['name'] = $tag;
                    $data['slug'] = SlugService::createSlug(Tag::class, 'slug', $tag);
                    $data['status'] = 'publish';
                    $data['pos'] = Tag::max('pos') + 1;
                    $tag_id = Tag::create($data);

                    $tags[] = $tag_id->id;
                }
            }
            $item->tags()->sync($tags);
        }
        return redirect()->route('blog.posts.index')->with([
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
        Creeper::canOrFail('read-posts');

        $post = Post::findOrFail($id);

        if (isset($post)) {
            $post = view('posts::Posts.read', compact('post'))->render();
            return response()->json($post, 200);
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
        Creeper::canOrFail('update-posts');

        $item = Post::findOrFail($id);
        $tags = Tag::where('status', 'publish')->orderBy('created_at', 'desc')->limit(8)->get();
        $categories = Category::all();

        if (isset($item)) {
            return view('posts::Posts.edit', compact('item', 'categories', 'tags'));
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
    public function update(UpdatePostRequest $request, $id)
    {
        Creeper::canOrFail('update-posts');

        $post = Post::find($id);

        //user activity
        $action = "Edit";
        $object = "Posts";
        $object_id = $post->id;
        $value_before = "Title:" . $post->title .
            "," .
            "Slug:" . $post->slug .
            "," .
            "Content:" . $post->content .
            "," .
            "Status:" . $post->status .
            "," .
            "Description:" . $post->description .
            "," .
            "Pos:" . $post->pos .
            "," .
            "Image: Edit Image" .
            "," .
            "Seo_title:" . $post->seo_title .
            "," .
            "Seo_keyword:" . $post->seo_keyword .
            "," .
            "Seo_description:" . $post->seo_description .
            "," .
            "Category_id:" . $post->category_id .
            "," .
            "Author_id:" . $post->author_id;

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
            "Image: Edit Image" .
            "," .
            "Seo_title:" . $request->seo_title .
            "," .
            "Seo_keyword:" . $request->seo_keyword .
            "," .
            "Seo_description:" . $request->seo_description .
            "," .
            "Category_id:" . $request->category_id .
            "," .
            "Author_id:" . $request->author_id;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        $post->title = $request->title;
        $post->slug = $request->slug;
        $post->image = $request->image;
        $post->content = $request->content;
        $post->description = $request->desc;
        $post->category_id = $request->category;
        $post->author_id = Auth::guard('admin')->user()->id;
        $post->status = $request->status;
        $post->pos = $request->pos;
        $post->seo_title = $request->seo_title;
        $post->seo_description = $request->seo_desc;
        $post->seo_keyword = $request->seo_keyword;
        $post->save();

        if ($request->has('tags')) {
            $tags = [];

            foreach ($request->tags as $tag) {
                $tag_id = Tag::where('id', $tag)->first();
                if (isset($tag_id)) {
                    $tags[] = $tag_id->id;
                } else {
                    $data['name'] = $tag;
                    $data['slug'] = SlugService::createSlug(Tag::class, 'slug', $tag);
                    $data['status'] = 'publish';
                    $data['pos'] = Tag::max('pos') + 1;
                    $tag_id = Tag::create($data);

                    $tags[] = $tag_id->id;
                }
            }
            $post->tags()->sync($tags);
        }
        return redirect()->route('blog.posts.index')->with([
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
        Creeper::canOrFail('delete-posts');

        $post = Post::findOrFail($id);
        //user activity
        $action = "Delete";
        $object = "Posts";
        $object_id = $post->id;
        $value_before = "Title:" . $post->title .
            "," .
            "Slug:" . $post->slug .
            "," .
            "Content:" . $post->content .
            "," .
            "Status:" . $post->status .
            "," .
            "Description:" . $post->description .
            "," .
            "Pos:" . $post->pos .
            "," .
            "Image: Delete Image" .
            "," .
            "Seo_title:" . $post->seo_title .
            "," .
            "Seo_keyword:" . $post->seo_keyword .
            "," .
            "Seo_description:" . $post->seo_description .
            "," .
            "Category_id:" . $post->category_id .
            "," .
            "Author_id:" . $post->author_id;

        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        if ($post) {
            // Force Delete

            $post->tags()->sync([]); // Delete relationship data

            $post->Delete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            return redirect()->back()->with([
                'status_type' => 'success',
                'status' => 'Post has been successfully deleted'
            ]);
        }
        $url = URL::previous();
        return $this->notFound($url);
    }

    public function deleteSelected(Request $request)
    {

        Creeper::canOrFail('delete-posts');

        $ids = $request->data;
        foreach ($ids as $id) {
            $post = Post::findOrFail($id);

            if (isset($post)) {
                // Force Delete

                $post->Delete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Posts has been successfully deleted');
        return response()->json(['status' => 'Posts has been successfully deleted'], 200);
    }

    public function getSlug(Request $request)
    {

        if ($request->type) {
            $slug = SlugService::createSlug(Post::class, 'slug', $request->slug, ['unique' => false]);
        } else {
            $slug = SlugService::createSlug(Post::class, 'slug', $request->slug);
        }

        return $slug;

    }

    public function getData()
    {
        Creeper::canOrFail('browse-posts');

        $model = new Post;

        if (isset(Auth::guard('admin')->user()->categories) && count(Auth::guard('admin')->user()->categories) != 0) {

            $cate_id = Auth::guard('admin')->user()->categories->pluck('id')->toArray();

            $model = $model->whereIn('category_id', $cate_id);

        }

        if (!Creeper::canOrabort('can-get-all')) {
            $model = $model->where('author_id', Auth::guard('admin')->user()->id);
        }

        $model = $model->with('category', 'author')->orderBy('created_at', 'desc');

        return DataTables::eloquent($model)
            ->addColumn('check', function ($model) {
                return '<input type="checkbox" class="check_item" name="id[]" value="' . $model->id . '" style="position: inherit; opacity: 1;">';
            })
            ->editColumn('image', function ($model) {
                return '<img src="' . $model->image . '" id="thumbnail" style="width:100%;" />';
            })
            ->addColumn('action', function ($model) {
                return '<button class="btn btn-circle btn-info read" type="button" data-toggle="tooltip" data-original-title="View" data-target="' . route('blog.posts.show',
                        $model->id) . '" onclick="read(this)"> <i class="fa fa-eye"></i> </button>
                            <a class="btn btn-circle btn-success edit" href="' . route('blog.posts.edit', $model->id) . '" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil"></i> </a>
                            <button class="btn btn-circle btn-danger delete" type="button" data-toggle="tooltip" onclick="deleteEl(this)" data-original-title="Delete" data-target="/' . $model->id . '" data-target-name="' . $model->display_name . '"> <i class="fa fa-close"></i> </button>';
            })
            ->rawColumns(['check', 'action', 'image'])
            ->make(true);
    }

    /*******************
     * Trash Functions *
     *******************/

    //call when restore trash
    public function restore($id)
    {
        $restoreTrash = Post::withTrashed()
            ->where('id', $id)
            ->restore();

        //user activity
        $action = "Restore";
        $object = "Posts";
        $object_id = $id;
        $value_before = null;
        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        return redirect()
            ->back()
            ->with(['status_type' => 'success', 'status' => 'Post has been successfully restore']);

    }

    //call when remove item form trash table
    public function remove($id)
    {

        $removeTrash = Post::withTrashed()
            ->where('id', $id)
            ->forceDelete();

        //user activity
        $action = "Remove";
        $object = "Posts";
        $object_id = $id;
        $value_before = null;
        $value_after = null;
        UserActivityHelpers::saveUserActivity($action, $object, $object_id, $value_before, $value_after);
        //end user activity

        return redirect()
            ->back()
            ->with(['status_type' => 'success', 'status' => 'Post has been successfully remove']);
    }

    public function deleteTrashSelected(Request $request)
    {

        Creeper::canOrFail('delete-posts');

        $ids = $request->data;

        foreach ($ids as $id) {
            $removeTrash = Post::withTrashed()->where('id', $id)->first();

            if (isset($removeTrash)) {
                // Force Delete

                $removeTrash->forceDelete(); // Now force delete will work regardless of whether the pivot table has cascading delete

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Posts has been successfully deleted');
        return response()->json(['status' => 'Posts has been successfully deleted'], 200);
    }

    public function restoreTrashSelected(Request $request)
    {

        Creeper::canOrFail('delete-posts');

        $ids = $request->data;

        foreach ($ids as $id) {
            $restoreTrash = Post::withTrashed()->where('id', $id)->first();

            if (isset($restoreTrash)) {
                // Force Delete

                $restoreTrash->restore(); // Now force delete will work regardless of whether the pivot table has cascading restore

            } else {
                continue;
            }
        }
        Session::flash('status_type', 'success');
        Session::flash('status', 'Posts has been successfully restored');
        return response()->json(['status' => 'Posts has been successfully restored'], 200);
    }
}
