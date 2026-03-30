<?php

namespace App\Http\Controllers\Backend;

use App\Models\Languages;
use App\Models\Translation;
use App\Traits\TranslateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Models\Community;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Intervention\Image\Facades\Image as FacadesImage;
use Validator;

class CommunitiesController extends Controller
{
    use TranslateTrait;
    public $moduleTitle,$moduleTitleSinglular,$moduleFolder;

    public function __construct()
    {
        $this->moduleTitle = "Communities";
        $this->moduleTitleSinglular = "Community";
        $this->moduleFolder = "communities";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $communities = Community::orderBy('sequence', 'ASC')->paginate(20);

        $data = array(
          'pageTitle'=> $this->moduleTitle,
          'items'=> $communities,
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
            'name'=>'required|max:190',
        ]);

        $query = new Community;
        $inputs = $request->input();
        $inputs = prepare_inputs($inputs);

        foreach($inputs as $field => $input){
            $query->{$field} = $input;
        }
        // Always Active...
        $query->is_publish = 0;
        $query->save();

        $data = [ 'success' => $this->moduleTitleSinglular.' Created' ];

        return redirect(admin_url($this->moduleFolder.'/'.$query->id.'/edit'))->with($data);
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
        $community = Community::find($id);
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
          'item'       => $community,
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

        $validator = FacadesValidator::make($request->all(), $rules);

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
            $query = Community::find($id);

            // Upload and Resize Banner Image....
            $image_path = $this->moduleFolder.'/'.$query->id;

            $path_image = processImage($request->file('file'),$image_path,1920, 1080);

            
            $photos = isset($query->photos) ? $query->photos : [];

            $photos[] = $image_path.'/'.$path_image;

            // remove existing photos
            if(!empty($query->photo) && $photos[0] !== $query->photo)
            {
                Storage::delete($query->photo);
            }

            $query->photo = $photos[0];
            $query->photos = $photos;
            $query->save();

            $request->session()->flash('message_success', 'Successfully uploaded photo!');

            // $image_name = time().'.'.$request->file->getClientOriginalExtension();

            // $image = FacadesImage::make($request->file('file'));

            // $checkWidth = $image->width();
            // $checkheight = $image->height();

            // if($checkWidth < 2000 && $checkheight < 3000){
            //     // image->resize(1560, 935, function ($constraint)

            //     $image->resize(1920, 1080, function ($constraint) // resize to 16:9 ratio for full hd
            //     {
            //         $constraint->aspectRatio();
            //         $constraint->upsize();
            //     });

            //     // Store Image....
            //     $disk = "public";

            //     $path = Storage::disk($disk)->put($image_path.$image_name, $image->stream());

            //     $photos = isset($query->photos) ? $query->photos : [];

            //     $photos[] = $this->moduleFolder . '/'.$query->id.'/'. $image_name;

            //     // remove existing photos
            //     if(!empty($query->photo) && $photos[0] !== $query->photo)
            //     {
            //         Storage::delete($query->photo);
            //     }

            //     $query->photo = $photos[0];
            //     $query->photos = $photos;
            //     $query->save();

            //     $request->session()->flash('message_success', 'Successfully uploaded photo!');

            // }else{
            //     header('HTTP/1.1 500 Internal Server Error');
            //     header('Content-type: text/plain');
            //     $msg = 'Image width must not greater than 2000px or <br/>the height must not greater than 3000px.';
            //     exit($msg);
            // }

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
        $query = Community::find($id);

        if(isset($request->key)){
            $rkey = (int) $request->key;

            // Get photos array
            $photos = $query->photos ?? [];

            // Validate key exists
            if (!isset($photos[$rkey])) {
                return back()->with('message_error', 'Photo not found.');
            }

            // Store paths BEFORE unsetting
            $photoPath = $photos[$rkey];
            $thumbPath = $this->moduleFolder . '/thumbs/' . basename($photoPath);

            // Delete files
            Storage::delete($photoPath);
            Storage::delete($thumbPath);

            // Remove photo from array
            unset($photos[$rkey]);

            // Reindex array
            $photos = array_values($photos);

            // Save updated JSON
            $query->photo = count($photos) != 0 ? $photos[0] : null;
            $query->photos = $photos;
            $query->save();

            $request->session()->flash('message_success', 'Successfully deleted photo!');

        }else{
            if(!empty($query)){
                $thumb_path = $this->moduleFolder.'/thumbs/'.basename($query->photo);
                Storage::delete($query->photo);
                Storage::delete($thumb_path);
                $query->photo = '';
                $query->save();
                $request->session()->flash('message_success', 'Successfully deleted photo!');
            }
        }


        return redirect('admin/'.$this->moduleFolder.'/'.$query->id.'/edit?tab=photo');
    }

    public function update(Request $request, $id)
    {
        $item = Community::find($id);

        $request->validate(
            [
                'is_publish'=>'required',
                'name'=>'required|max:190',
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

        return redirect(admin_url($this->moduleFolder.'/'.$id.'/edit'))->with($data);

    }

    public function delete($id)
    {
        // delete
        $query = Community::find($id);

        if($query->photo){
            Storage::delete($query->photo);
        }

        if($query->photos){
            foreach($query->photos as $single){
                Storage::delete($single);
            }
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
            $photo = Community::find($id);
            $photo->sequence = $order;
            $photo->save();
		}
    }

    public function get_all_communities(Request $request){
        //init
        $q =(!empty($request->q)) ? $request->q : "";
        $items = [];
        $where = [];

        //query
        $where[] = ['is_publish', 1];
        if( !empty($q) ){
            $where[] = ['name', 'LIKE', $q."%"];
        }

        $communities = Community::where($where)->get();
        
        //loop
        foreach( $communities as $single){
            $single->name = $single->name;
            $items[] = $single;
        }

        // ARRAY PAGINATION
        $page = ! empty( $request->page ) ? (int) $request->page : 1;
        $total = count( $items ); //total items in array
        $limit = 10; //per page
        $totalPages = ceil( $total/ $limit ); //calculate total pages
        //$page = max($page, 1); //get 1 page when$request->page <= 0
        $page = min($page, $totalPages); //get last page when $request->page > $totalPages
        $offset = ($page - 1) * $limit;
        if( $offset < 0 ) $offset = 0;
        $itemsSliced = array_slice( $items, $offset, $limit );
        $morePages = $page + 1;
        // END OF ARRAY PAGINATION

        //JSON
        $json["q"] = $q;
        $json["pagination"] = array( "more" => $morePages );
        $json["total_count"] = count($items);
        $json["page"] = $page;
        $json["items"] = $itemsSliced;

        echo json_encode( $json );
    }

}
