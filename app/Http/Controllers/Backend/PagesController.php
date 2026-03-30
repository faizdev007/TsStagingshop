<?php

namespace App\Http\Controllers\Backend;

use App\Models\Languages;
use App\Models\Translation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Page;
use Validator;
use Cviebrock\EloquentSluggable\Services\SlugService;
use App\Traits\ImageTrait;
use App\Traits\TranslateTrait;
use File;
use Illuminate\Support\Str;

class PagesController extends Controller
{
    use TranslateTrait;

    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pages = Page::orderBy('route', 'ASC')
          // filter other data
          ->when($request->input('q'), function($query) use ($request){
              return $query->where(function ($query) use ($request) {
                      $query->where('title', 'like', '%'.$request->input('q').'%')
                          ->orWhere('content', 'like', '%'.$request->input('q').'%');
                  });
          })
          ->paginate(20);

        $data = array(
          'pageTitle'=>'Pages',
          'pages'=> $pages,
          'request'=> $request,
        );
        return view('backend.pages.index')->with($data);
    }

    public function create()
    {
        return view('backend.pages.create', [
            'pageTitle' => 'Create Page',
            'tab' => 0
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'title'     => 'required|max:80',
                'route'     => 'required',
                'content'   => 'required',
                'page_type' => 'required'
            ]
        );

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            // Get Page Type...
            $page_type = $request->input('page_type');

            // Save The Page (As Additional Page)...
            $page = new Page;
            $page->title = $request->input('title');
            $page->header_title = $request->input('header_title');
            $page->route = ($page->route != '/') ? str_replace('/', '', $request->input('route')) : $page->route; // Strip (/) from string...
            $page->content = $request->input('content');
            $page->page_type = $page_type;
            $page->save();

            if($page_type == 'page')
            {
                // Now Redirect to Edit Facility...
                $request->session()->flash('message_success', 'Successfully created page!');
                return redirect('admin/pages/'.$page->id.'/edit');
            }
            else
            {
                // Now Redirect to Edit Facility...
                $request->session()->flash('message_success', 'Successfully created page!');
                return redirect('admin/bespoke-pages/'.$page->id.'/edit?tab=photo');
            }
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
        $page = Page::find($id);
        $tab = $request->input('tab');

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
          'pageTitle'   =>  'Edit Page',
          'page'       =>  $page,
          'languages' => $languages,
          'tab'       =>  $tab,
        );

        return view('backend.pages.edit')->with($data);
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
            'title'         => 'required|max:190',
            'content'       => 'required|max:60000',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {

            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message)
            {
                $error_txt .= "$message ";
            }
            $request->session()->flash('message_danger', $error_txt);

            //return redirect('admin/pages/'.$id.'/edit')->withErrors($validator);
            return redirect('admin/pages/'.$id.'/edit')->withInput();

        }
        else
        {
            $page = Page::find($id);
            $page->title = $request->input('title');
            $page->content = $request->input('content');
            $page->header_title = $request->input('header_title');
            $page->content1 = $request->input('content1');
            $page->heading1 = $request->input('heading1');
            $page->heading2 = $request->input('heading2');
            $page->content2 = $request->input('content2');
            $page->content3 = $request->input('content3');

            if($page->page_type == 'bespoke')
            {
                $page->route = ($page->route != '/') ? str_replace('/', '', $request->input('route')) : $page->route; // Strip (/) from string...
            }
            $page->save();

            if(settings('translations'))
            {
                //To note adding new field must also add in app/Models/Translation fillable
                $translationFields = [
                    'title',
                    'content',
                ];
                $this->saveTranslation($request, $page, $translationFields, 'App\Page'); //Saving translations...
            }

            // redirect
            $request->session()->flash('message_success', 'Successfully updated page!');

            if($page->page_type == 'bespoke')
            {
                return redirect('admin/bespoke-pages/'.$page->id.'/edit');
            }
            else
            {
                return redirect('admin/pages/'.$page->id.'/edit');
            }
        }
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Page Deleted!'
        ]);
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
        $segment = $request->input('segment');

        // store
        $page = Page::find($id);
        $meta = [
          'title' => $request->input('meta_title'),
          'description' => $request->input('meta_description'),
          'keywords' => $request->input('meta_keywords')
        ];
        $page->meta = json_encode($meta);
        $page->save();

        if(settings('translations'))
        {
            //To note adding new field must also add in app/Models/Translation fillable
            $translationFields = [
                'meta',
            ];
            $this->saveTranslation($request, $page, $translationFields, 'App\Page'); //Saving translations...
        }

        // redirect
        $request->session()->flash('message_success', 'Successfully updated metadata!');
        return redirect('admin/'.$segment.'/'.$page->id.'/edit?tab=meta');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function upload(Request $request, $id)
     {
         // Validate
         $rules = array(
             'file' => 'required|max:10240|mimes:jpeg,png,gif,tif,webp',
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

                //  $filenameWithExt = $request->file('file')->getClientOriginalName();
                //  $extension = $request->file('file')->getClientOriginalExtension();
                //  $fileNameToStore = uniqid() . '.' . strtolower($extension);
                //  $path_properties = 'app/public/pages/';

                $path_image = processImage($request->file('file'),'/pages',1500);

                //  $getRealPath = ($_FILES['file']['tmp_name']);

                // store file to db
                 $page = Page::find($id);

                // remove existing photo
                 if(!empty($page->photo)){
                     Storage::delete($page->photo);
                 }

                //  // resize photo
                //  $img = Image::make($getRealPath);
                //  $img->resize(1500, 1500, function ($constraint) {
                //      $constraint->aspectRatio();
                //      $constraint->upsize();
                //  });

                //  //Store image
                //  File::makeDirectory(storage_path($path_properties), $mode = 0777, true, true);
                //  $path_image = $img->save(storage_path($path_properties . $fileNameToStore));
                 $path_save_image = 'pages/' . $path_image;

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
        $page = Page::find($id);

        if(!empty($page))
        {
            $thumb_path = 'pages/thumbs/'.basename($page->photo);
            Storage::delete($page->photo);
            Storage::delete($thumb_path);
            $page->photo = '';
            $page->save();
            $request->session()->flash('message_success', 'Successfully deleted photo!');
        }

        return redirect('admin/pages/'.$page->id.'/edit?tab=photo');
    }

    public function generate_slug(Request $request)
    {
        $slug = SlugService::createSlug(Page::class, 'route', $request->input('title'));

        return $slug;
    }

}
