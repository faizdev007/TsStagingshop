<?php

namespace App\Http\Controllers\Backend;

use App\Models\Languages;
use App\Models\Translation;
use App\Traits\TranslateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Models\PostCategory;
use Validator;

class NewsCategoryController extends Controller
{
    use TranslateTrait;

    public function __construct()
    {
        $this->moduleTitle = "Blog Categories";
        $this->moduleTitleSinglular = "Blog Category";
        $this->moduleFolder = "postcategories";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = PostCategory::orderBy('created_at', 'DESC')->paginate(20);

        $data = array(
          'pageTitle'=> $this->moduleTitle,
          'items'=> $categories,
          'module_title'  => $this->moduleTitle,
          'folder'  => $this->moduleFolder,
        );
        return view('backend.'.$this->moduleFolder.'.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array
            (
                'pageTitle' => 'Create '.$this->moduleTitleSinglular,
                'folder' => $this->moduleFolder
            );
        return view('backend.'.$this->moduleFolder.'.create')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:post_categories|max:190',
        ]);

        $query = new PostCategory;
        $inputs = $request->input();
        $inputs = prepare_inputs($inputs);

        foreach($inputs as $field => $input){
            $query->{$field} = $input;
        }
        // Always Active...
        $query->is_publish = 1;
        $query->save();

        $data = [ 'success' => $this->moduleTitleSinglular.' Created' ];

        return redirect(admin_url($this->moduleFolder))->with($data);
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
        $category = PostCategory::find($id);
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
          'pageTitle'   => 'Edit '.$this->moduleTitleSinglular,
          'item'       => $category,
          'tab'         => $tab,
          'languages'   => $languages,
          'module_title'  => $this->moduleTitle,
          'folder'  => $this->moduleFolder,
        );

        return view('backend.'.$this->moduleFolder.'.edit')->with($data);
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
            'file' => 'required|max:10240|mimes:jpeg,png',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_txt = false;

            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }

            $request->session()->flash('message_danger', $error_txt);

        }
        else
        {
            // store file to db
            $query = PostCategory::find($id);

            // remove existing photo
            if(!empty($query->photo))
            {
                Storage::delete($query->photo);
            }

            // Upload and Resize Banner Image....
            $image_path = $this->moduleFolder.'/';
            $image_name = time().'.'.$request->file->getClientOriginalExtension();

            $image = Image::make($request->file('file'));

            $checkWidth = $image->width();
            $checkheight = $image->height();

            if($checkWidth < 2000 && $checkheight < 3000){

                $image->resize(1560, 935, function ($constraint)
                {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                // Store Image....
                $disk = "public";
                $path = Storage::disk($disk)->put($image_path.$image_name, $image->stream());

                $query->photo = $this->moduleFolder.'/'.$image_name;
                $query->save();

                $request->session()->flash('message_success', 'Successfully uploaded photo!');

            }else{
                header('HTTP/1.1 500 Internal Server Error');
                header('Content-type: text/plain');
                $msg = 'Image width must not greater than 2000px or <br/>the height must not greater than 3000px.';
                exit($msg);
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
        $query = PostCategory::find($id);

        if(!empty($query)){
            $thumb_path = $this->moduleFolder.'/thumbs/'.basename($query->photo);
            if($query->photo){
                Storage::delete($query->photo);
                Storage::delete($thumb_path);
            }
            $query->photo = '';
            $query->save();
            $request->session()->flash('message_success', 'Successfully deleted photo!');
        }

        return redirect('admin/'.$this->moduleFolder.'/'.$query->id.'/edit?tab=photo');
    }

    public function update(Request $request, $id)
    {
        $item = PostCategory::find($id);

        $request->validate(
            [
                'is_publish'=>'required',
                'name'=>'required|unique:post_categories|max:190',
            ]
        );

        $inputs = $request->input();
        $inputs = prepare_inputs($inputs);

        foreach($inputs as $field => $input)
        {
            $item->{$field} = $input;
        }

        $item->save();

        $data = [ 'success' => 'Entry Updated' ];

        return redirect(admin_url($this->moduleFolder))->with($data);

    }

    public function delete($id)
    {
        // delete
        $query = PostCategory::find($id);
        if($query->photo){
            Storage::delete($query->photo);
        }
        $query->delete();

        $data = ['success' => 'Successfully deleted the slide!'];
        return redirect(admin_url($this->moduleFolder.'/'))->with($data);
    }


    public function sequence(Request $request)
    {
        $items = $request->input('item');
        print_r($items);
        foreach($items as $order => $id){
            $photo = PostCategory::find($id);
            $photo->sequence = $order;
            $photo->save();
		}
    }


}
