<?php

namespace App\Http\Controllers\Backend;

use App\Models\Languages;
use App\Models\Translation;
use App\PostTag;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Post;
use Validator;
use App\Traits\TranslateTrait;
use File;

class NewsController extends Controller
{

    use TranslateTrait;

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
                $request->session()->flash('message_danger', 'You do not have access to this resource');
                return redirect('/admin');
            }

        });

        $this->middleware(['auth','admin']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$news = Post::orderBy('id', 'DESC')
        $news = Post::orderBy('date_published', 'DESC') //Jan: Changed to order by date published

            // get active news
            ->where(function($query) use ($request){
                $status = $request->input('status');
                if(!empty($status) && $status == 'draft'){
                    $query->where('status', 'draft');
                }elseif(!empty($status) && $status == 'published'){
                    $query->where('status', 'published');
                }elseif(!empty($status) && $status == 'archived'){
                    $query->where('status', 'deleted');
                }else{
                    //$query->where('status', '!=', 'deleted')
                    //->orWhere('status', null);
                }

                $category = $request->input('category');
                if(!empty($category)){
                    $query->whereHas('postTags', function ($query) use ($category){
                        $query->where('post_tag_value', $category);
                    });
                }

            })

            // filter other data
            ->when($request->input('q'), function($query) use ($request){
                return $query->where(function ($query) use ($request) {
                       $query->where('title', 'like', '%'.$request->input('q').'%')
                        ->orWhere('content', 'like', '%'.$request->input('q').'%');
                });
              })
            ->paginate(10);



        $categories = PostCategory::where('is_publish', 1)->orderBy('created_at', 'DESC')->get();

        $data = array(
          'pageTitle'=>'News',
          'news'=> $news,
          'request'=> $request,
          'categories' => $categories

        );
        return view('backend.news.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(settings('translations'))
        {
            $languages = Languages::first();
            $languages = $languages->languages_friendly_array;
        }
        else
        {
            $languages = '';
        }

        $postTags = PostCategory::where('is_publish', 1)->orderBy('created_at', 'DESC')->get();

        $postTagsArray=[];
        if(count($postTags)){
            foreach($postTags as $tag){
                $postTagsArray[$tag->id] = $tag->name;
            }
        }

        $data = array
            (
                'languages' => $languages,
                'pageTitle' =>'Create Article',
                'postTags' => $postTagsArray
            );
        return view('backend.news.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     public function store(Request $request)
     {
         // validate
         $rules = array(
             'title'       => 'required|max:190',
             'content'       => 'required|max:60000',
             'date_published'       => 'required',
         );
         $validator = Validator::make($request->all(), $rules);

         // process the login
         if ($validator->fails()) {
             $errors = $validator->errors();
             $error_txt = false;
             foreach ($errors->all() as $message) {
                 $error_txt .= "$message ";
             }
             $request->session()->flash('message_danger', $error_txt);

             return redirect('admin/news/create')
                 ->withInput($request->all());
         } else {

             // store
             $article = new Post;
             $article->title = $request->input('title');
             //$article->subtitle = $request->input('subtitle');
             //$article->label = $request->input('label');
             $article->content = $request->input('content');
             $article->status = $request->input('status');
             $article->date_published = $request->input('date_published');
             $article->save();

             // If Article has any tags, Add them...
             $location_tags = $request->input('location_tags');

             if(!empty($location_tags))
             {
                 foreach($location_tags as $location_tag)
                 {
                     if(!empty($location_tag)){
                         $tag = new PostTag;
                         $tag->post_tag_value = strtolower($location_tag);
                         $tag->post_id = $article->id;
                         $tag->type = 'post_categories';
                         $tag->save();
                     }
                 }
             }

             // If Translations are Set....
             if(settings('translations'))
             {
                 $set_languages = $this->get_set_languages();

                 foreach($set_languages->languages_array as $language)
                 {
                     // Create The Translation....
                     $translations = array(
                         'title'                 => $request->input('title_' . $language . ''),
                         'content'               => $request->input('content_' . $language . ''),
                         'language'              => $language,
                         'translationable_id'    => $article->id,
                         'translationable_type'  => 'App\Article'
                     );

                     $article->translations()->create($translations);
                 }
             }

             // redirect
             $request->session()->flash('message_success', 'Successfully created article!');
             return redirect('admin/news/'.$article->id.'/edit');
         }
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function edit(Request $request, $id)
     {
         // get the slide
         $article = Post::find($id);
         $tab = $request->input('tab');

         //$postTags = PostTag::get();
         $postTags = PostCategory::where('is_publish', 1)->orderBy('created_at', 'DESC')->get();

         $postTagsArray=[];
         if(count($postTags)){
             foreach($postTags as $tag){
                 $postTagsArray[$tag->id] = $tag->name;
             }
         }

         $postTagsSelected=[];
         if(!$article->category_tags->isEmpty()){
             foreach($article->category_tags as $tag):
                 if(!empty($tag->categoryActive)){
                     $postTagsSelected[$tag->categoryActive->id] = $tag->categoryActive->name;
                 }
             endforeach;
         }

         if(settings('translations'))
         {
             $languages = Languages::first();
             $languages = $languages->languages_friendly_array;
         }
         else
         {
             $languages = '';
         }

         $data = array(
           'pageTitle'   =>  'Edit Article',
           'article'     =>  $article,
           'languages'   =>  $languages,
           'tab'         =>  $tab,
           'postTags'   =>  $postTagsArray,
           'postTagsSelected' => $postTagsSelected,
         );

         return view('backend.news.edit')->with($data);
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // validate
        $rules = array(
            'title'       => 'required|max:190',
            'content'       => 'required|max:60000',
        );

        $validator = Validator::make($request->all(), $rules);

        $article = Post::find($id);

        // process the login
        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }
            $request->session()->flash('message_danger', $error_txt);

            return redirect('admin/news/'.$article->id.'/edit')
                ->withInput($request->all());
        } else {

            // store
            $article->title = $request->input('title');

            $article->slug = null;

            $article->content = $request->input('content');
            if($article->status != 'deleted'){
                $article->status = $request->input('status');
            }
            $article->date_published = $request->input('date_published');

            // set date published
            if($article->status == 'published' && empty($article->date_published))
            {
                //$article->date_published = date('Y-m-d');
            }

            $article->save();

            // If Article has any tags, Add them...
            $location_tags = $request->input('location_tags');

            // Clear Current Tags...
            $article->category_tags()->delete();

            if(!empty($location_tags))
            {
                // Clear Current Tags...
                $article->location_tags()->delete();

                foreach ($location_tags as $location_tag)
                {
                    // Create New Ones....
                    if(!empty($location_tag)){
                        $tag = new PostTag;
                        $tag->post_tag_value = $location_tag;
                        $tag->post_id = $id;
                        $tag->type = 'post_categories';
                        $tag->save();
                    }
                }
            }

            // If We have Translations - Update Them....
            if(settings('translations'))
            {
                $set_languages = $this->get_set_languages();

                // Update any Existing Pivots...
                $existing_trans = $article->translations;

                if(!$existing_trans->isEmpty())
                {
                    foreach ($set_languages->languages_array as $language)
                    {
                        $title = $request->input('title_'.$language.'');
                        $content = $request->input('content_'.$language.'');

                        foreach($existing_trans as $trans)
                        {
                            // Find The Trans
                            $update_trans = Translation::where('id', $trans->id)->where('language', $language)->first();

                            if($update_trans)
                            {
                                // Update...
                                $save_trans = Translation::find($update_trans->id);
                                $save_trans->title = $title;
                                $save_trans->content = $content;
                                $save_trans->save();
                            }
                        }
                    }

                    // Create New Translation...
                    $already_set = array();
                    foreach($existing_trans as $trans)
                    {
                        $already_set[] = $trans->language;
                    }

                    foreach($set_languages->languages_array as $language)
                    {
                        $title = $request->input('title_'.$language.'');
                        $content = $request->input('content_'.$language.'');

                        if (!in_array($language, $already_set))
                        {
                            // Create New Translation
                            if(isset($title) || isset($content))
                            {
                                $translations = array(
                                    'title' => $title,
                                    'content' => $content,
                                    'language' => $language,
                                    'translationable_id' => $article->id,
                                    'translationable_type'  => 'App\Post'
                                );

                                $article->translations()->create($translations);
                            }
                        }
                    }
                }
                else
                {
                    foreach($set_languages->languages_array as $language)
                    {
                        $title = $request->input('title_' . $language . '');
                        $content = $request->input('content_' . $language . '');

                        // Create New Translation
                        if(isset($title) || isset($content))
                        {
                            $translations = array(
                                'title' => $title,
                                'content' => $content,
                                'language' => $language,
                                'translationable_id' => $article->id,
                                'translationable_type'  => 'App\Post'
                            );

                            $article->translations()->create($translations);
                        }
                    }
                }
            }

            // redirect
            $request->session()->flash('message_success', 'Successfully updated article!');
            return redirect('admin/news/'.$article->id.'/edit');
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_meta(Request $request, $id)
    {
        // store
        $article = Post::find($id);
        $meta = [
          'title' => $request->input('meta_title'),
          'description' => $request->input('meta_description'),
          'keywords' => $request->input('meta_keywords')
        ];
        $article->meta = json_encode($meta);
        $article->save();

        if(settings('translations'))
        {
            //To note adding new field must also add in app/Models/Translation fillable
            $translationFields = [
                'meta',
            ];
            $this->saveTranslation($request, $article, $translationFields, 'App\Post'); //Saving translations...
        }

        // redirect
        $request->session()->flash('message_success', 'Successfully updated metadata!');

        return redirect('admin/news/'.$article->id.'/edit?tab=meta');
    }

    public function upload(Request $request, $id)
    {

        // Validate
        $rules = array(
            'file' => 'required|max:5999|mimes:jpeg,png,gif,tif,webp',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_txt = false;

            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }

            $request->session()->flash('message_danger', $error_txt);

        } else {

            if ($request->hasFile('file')) {

                // $filenameWithExt = $request->file('file')->getClientOriginalName();
                // $extension = $request->file('file')->getClientOriginalExtension();
                // $fileNameToStore = uniqid() . '.' . strtolower($extension);
                // $path_properties = 'app/public/posts/';

                $path_image = processImage($request->file('file'),'/posts',900);

                // $getRealPath = ($_FILES['file']['tmp_name']);

                // store file to db
                $page = Post::find($id);

                // remove existing photo
                if(!empty($page->photo)){
                    Storage::delete($page->photo);
                }

                // // resize photo
                // $img = Image::make($getRealPath);
                // $img->resize(900, 900, function ($constraint) {
                //     $constraint->aspectRatio();
                //     $constraint->upsize();
                // });

                // //Store image
                // File::makeDirectory(storage_path($path_properties), $mode = 0777, true, true);
                // $path_image = $img->save(storage_path($path_properties . $fileNameToStore));
                $path_save_image = 'posts/' . $path_image;

                if (!empty($path_image)) {
                    $page->photo = $path_save_image;
                    $page->save();
                }

                $request->session()->flash('message_success', 'Successfully uploaded photo!');

            }else{
                $request->session()->flash('message_danger', 'Error uploaded photo!');
            }


        }

        return 'completed';
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_photo(Request $request, $id)
    {
        $article = Post::find($id);

        if(!empty($article)){
            $thumb_path = 'posts/thumbs/'.basename($article->photo);
            Storage::delete($article->photo);
            Storage::delete($thumb_path);
            $article->photo = '';
            $article->save();
            $request->session()->flash('message_success', 'Successfully deleted photo!');
        }

        return redirect('admin/news/'.$article->id.'/edit?tab=photo');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request, $id)
    {
        $article = Post::find($id);

        if(!empty($article)){
            $article->status = 'draft';
            $article->save();
            $request->session()->flash('message_success', 'Successfully restored as draft!');
        }

        return redirect('admin/news/'.$article->id.'/edit');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy(Request $request, $id)
    {
        // delete
        $article = Post::find($id);

        if($article)
        {
            $article->status = 'deleted';
            $article->save();

            // Send Confirmed Response...
            return response()->json([
                'error'     => 'false',
                'redirect'  => '/admin/news',
                'message'   => 'Article Removed!'
            ]);

        }
    }


    public function delete($id)
    {
        // delete
        $article = Post::find($id);
        //$article->status = 'deleted';

        $thumb_path = 'posts/'.basename($article->photo);
        Storage::delete($article->photo);
        Storage::delete($thumb_path);


        $article->delete();
        $data = ['success' => 'Successfully deleted the article!'];
        return redirect(admin_url('news/'))->with($data);
    }

    /**
     * This Function get's all Property Locations
     * That are set in the Properties Table
     * Allowing us to target specific news to that area..
     */

    public function get_locations()
    {

    }

}
