<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Post;
use App\Models\PostCategory;
//use App\PostTag;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Traits\TemplateTrait;
use App\Page;

class NewsController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        // Minor Middleware to Check if Module is Active....
        $this->middleware(function ($request, $next)
        {
            if(settings('show_blog'))
            {
                return $next($request);
            }
            else
            {
                return redirect('/');
            }

        });
    }

    /*
    * News page
    *
    */
    public function index(Request $request)
    {


        //metadata
        //$meta_data = new \stdClass();
        //$meta_data->title = 'Altman Living';
        //$meta = get_metadata($meta_data);

        $filterCategory = $request->input('category');

        $query = Post::published()->orderBy('date_published', 'DESC');

        $posts = $query->paginate(4);
        


        /*
        if( empty($request->input('page')) || $request->input('page') == 1 ){
            $primary = $query->paginate(1);
        }else{
            $primary = [];
        }
        */

        $categories = PostCategory::where('is_publish', 1)->orderBy('created_at', 'DESC')->get();

        $page = Page::where('route', 'blog-page')->firstOrFail();
        $meta = get_metadata($page);

        return view('frontend.demo1.news', [
            'posts' => $posts,
            'meta' => $meta,
            'page' => $page,
            //'primary' => $primary,
            'categories' => $categories,
            'request' => $request
        ]);
    }

    /*
    * Article page
    *
    */
    public function article($slug)
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        //metadata
        $meta = get_metadata($post);

        return view('frontend.demo1.article', [
            'post' => $post,
            'meta' => $meta,
        ]);
    }

    /*
    * News search page
    *
    */
    public function search(Request $request)
    {



        $keyword = $request->input('keyword');

        $query = Post::published()->orderBy('date_published', 'DESC');

        if($keyword){            
            $query->where(function($query) use ( $keyword )
            {
                $query->orwhere([['title', 'LIKE', '%'.$keyword.'%']]);
                $query->orwhere([['content', 'LIKE', '%'.$keyword.'%']]);
            });
        }
        $posts = $query->paginate(4);
        /*
        if( empty($request->input('page')) || $request->input('page') == 1 ){
            $primary = $query->paginate(1);
        }else{
            $primary = [];
        }
        */

        $categories = PostCategory::where('is_publish', 1)->orderBy('created_at', 'DESC')->get();

        $page = Page::where('route', 'blog-page')->firstOrFail();
        $meta = get_metadata($page);

        return view('frontend.demo1.news', [
            'posts' => $posts,
            'meta' => $meta,
            'page' => $page,
            //'primary' => $primary,
            'categories' => $categories,
            'request' => $request
        ]);
    }


}
