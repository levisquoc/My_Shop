<?php

namespace App\Http\Controllers;

use App\Facade\Creeper;
use App\Helpers\ErrorHelpers;
use App\Modules\Pages\Models\Page;
use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Post;
use App\Modules\Contacts\Models\Contact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Mail;

class HomeController extends Controller
{
    use ErrorHelpers;

    private $lifetime;

    public function __construct()
    {
        $this->lifetime = Carbon::now()->addMonth();
    }

    public function Navigation($slug)
    {

        if (cache($slug) == 'post') {
            return $this->getListByCate($slug);
        } elseif (cache($slug) == 'page') {
            return $this->getPage($slug);
        }

    }

    public function index()
    {
        $categories = Creeper::getList('App\Modules\Posts\Models\Category', 'index', 'publish', null,true);

        $posts_sidebar = Creeper::getList('App\Modules\Posts\Models\Post', 'sidebar_index', 'publish', 5,true);
        $posts_slide = Creeper::getList('App\Modules\Posts\Models\Post', 'slide_index', 'publish', 8,false, 'pos');
        return view('site.index', compact('categories', 'posts_sidebar', 'posts_slide'));
    }

    public function getListByCate($slug)
    {
        $category = Cache::remember('getListByCate_' . $slug, $this->lifetime, function () use ($slug) {
            return Category::where('slug', $slug)->first();
        });
        if (isset($category)) {
            $posts = $category->posts;
            return view('site.blogs', compact('category', 'posts'));
        }
        return false;
    }

    public function blogDetail($cate, $slug)
    {
        $post = Cache::remember('blog_detail_' . $slug, $this->lifetime, function () use ($slug) {
            return Post::where('slug', $slug)->first();
        });
        if (isset($post)) {
            return view('site.blog-detail', compact('post', 'cate'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }

    public function getPage($slug)
    {
        $page = Cache::remember('get_page_' . $slug, $this->lifetime, function () use ($slug) {
            return Page::where('slug', $slug)->first();
        });
        if (isset($page)) {
            return view('site.page', compact('page'));
        }

        $url = URL::previous();
        return $this->notFound($url);
    }

    public function contact(Request $request)
    {
        if ($request->get('g-recaptcha-response')) {
            $data = $request->all();

            if (Contact::create($data)) {
                Mail::send('emails.contact_mail',
                    [
                        'url' => url(''),
                        'email' => $request->email,
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'mes' => $request->message,
                        'subject' => $request->subject,
                    ], function ($message) use ($request) {
                        $message->to($request->email);
                        $message->cc('contact@vivaviet.vn');
                        $message->from('noreply@vivaviet.vn');
                        $message->subject('Mail thông báo từ ' . url(''));
                    });

                return redirect()->back()->with(['status' => 'success', 'mes' => trans('language.send_success')]);
            }
        } else {
            return redirect()->back()->with(['status' => 'error', 'mes' => trans('language.send_fail')]);
        }

    }

    public function emailSubcrible(Request $request)
    {
        if (!Contact::where('email', $request->email)->where('subject', 'E-Mail subcrible')->first()) {
            $data['email'] = $request->email;
            $data['subject'] = 'E-Mail subcrible';
            if (Contact::create($data)) {
                Mail::send('emails.subcrible',
                    [
                        'url' => url(''),
                        'email' => $request->email
                    ], function ($message) use ($request) {
                        $message->to($request->email);
                        $message->cc('contact@vivaviet.vn');
                        $message->from('noreply@vivaviet.vn');
                        $message->subject('Mail thông báo từ ' . url(''));
                    });

                return redirect()->back()->with(['status' => 'success', 'mes' => trans('language.subcrible_success')]);
            }

        } else {
            return redirect()->back()->with(['status' => 'error', 'mes' => trans('language.emai_exists')]);
        }
    }

}
