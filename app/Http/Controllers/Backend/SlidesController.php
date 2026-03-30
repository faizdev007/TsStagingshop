<?php

namespace App\Http\Controllers\Backend;

use App\Models\Languages;
use App\Models\Translation;
use App\Traits\TranslateTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Slide;
use Validator;

class SlidesController extends Controller
{
    use TranslateTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slides = Slide::orderBy('priority', 'ASC')->paginate(20);

        $data = array(
          'pageTitle'=>'Slides',
          'slides'=> $slides
        );
        return view('backend.slides.index')->with($data);
    }


    /**
     * Sort items through drag
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id){
            $slide = Slide::find($id);
            $slide->priority = $order;
            $slide->save();
        }

        echo 'completed!';
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

        $data = array(
          'pageTitle'=>'Create Slide',
          'languages' => $languages
        );

        return view('backend.slides.create')->with($data);
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
            'text_line1'       => 'required|max:190',
            'text_line2'       => 'max:190',
            'type'             => 'required'
        );
        $validator = Validator::make($request->all(), $rules);
        //$request->session()->flash('status', 'Task was successful!');
        // process the login
        if ($validator->fails())
        {
            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message)
            {
                $error_txt .= "$message ";
            }
            $request->session()->flash('message_danger', $error_txt);

            return redirect('admin/slides/create')
                ->withInput($request->all());
        }
        else
        {
            // store
            $slide = new Slide;
            $slide->text_line1 = $request->input('text_line1');
            $slide->text_line2 = $request->input('text_line2');
            $slide->type = $request->input('type');
            $slide->priority = 0;
            $slide->save();

            if(settings('translations'))
            {
                $set_languages = $this->get_set_languages();
                foreach($set_languages->languages_array as $language)
                {
                    $line1 = $request->input('text_line1_'.$language.'');
                    $line2 = $request->input('text_line2_'.$language.'');
                    if(isset($line1) || isset($line2))
                    {
                        // Create The Translation....
                        $translations = array(
                            'text_line1'            => $line1,
                            'text_line2'            => $line2,
                            'language'              => $language,
                            'translationable_id'    => $slide->id,
                            'translationable_type'  => 'App\Slide'
                        );

                        $slide->translations()->create($translations);
                    }
                }
            }

            // redirect
            $request->session()->flash('message_success', 'Successfully created slide!');
            return redirect('admin/slides/'.$slide->id.'/edit');
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
        $slide = Slide::find($id);
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
          'pageTitle'   => 'Edit Slide',
          'slide'       => $slide,
          'tab'         => $tab,
          'languages'   => $languages
        );

        return view('backend.slides.edit')->with($data);
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
            'file' => 'required|max:10240|mimes:jpeg,png,webp',
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
            $slide = Slide::find($id);

            // remove existing photo
            if(!empty($slide->photo))
            {
                $thumb_path = 'slides/thumbs/'.basename($slide->photo);
                Storage::delete($slide->photo);
                Storage::delete($thumb_path);
            }

            // Upload and Resize Banner Image....
            $image_path = '/slides';

            $path_image = processImage($request->file('file'),$image_path,1080,720);

            // $image_name = time().'.'.$request->file->getClientOriginalExtension();

            // // create slides directory
            // if(!Storage::disk('local')->exists('public/slides')){
            //     Storage::disk('local')->makeDirectory('public/slides');
            // }

            // $image = Image::make($request->file('file'));
            // $image->resize(1560, 935, function ($constraint)
            // {
            //     $constraint->aspectRatio();
            //     $constraint->upsize();
            // });

            // // Store Image....
            // $image->save(storage_path($image_path . $image_name));

            $slide->photo = 'slides'.'/'.$path_image;
            $slide->save();

            $request->session()->flash('message_success', 'Successfully uploaded photo!');
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
        $slide = Slide::find($id);

        if(!empty($slide)){
            $thumb_path = 'slides/thumbs/'.basename($slide->photo);
            Storage::delete($slide->photo);
            Storage::delete($thumb_path);
            $slide->photo = '';
            $slide->save();
            $request->session()->flash('message_success', 'Successfully deleted photo!');
        }

        return redirect('admin/slides/'.$slide->id.'/edit?tab=photo');
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
        $type = $request->input('type');

        if($type == "image")
        {
            // validate
            $rules = array(
                'text_line1'       => 'required|max:190',
                'text_line2'       => 'max:190'
            );
        }
        else
        {
            // validate
            $rules = array(
                //'text_line1'       => 'required|max:190',
                //'text_line2'       => 'max:190',
                'source'           => 'required',
                'video_id'         => 'required'
            );
        }

        $validator = Validator::make($request->all(), $rules);

        // process the login
        if ($validator->fails()) {


            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }
            $request->session()->flash('message_danger', $error_txt);

            return redirect('admin/slides/'.$id.'/edit')
                ->withInput($request->all());
        }
        else
        {
            // store
            $slide = Slide::find($id);
            $slide->text_line1 = $request->input('text_line1');
            $slide->text_line2 = $request->input('text_line2');

            if($type == "video")
            {
                $slide->source = $request->input('source');
                $slide->video_id = $request->input('video_id');
                $show_text = $request->input('show_text');
                $slide->show_text = (isset($show_text)) ? 1 : 0;
            }

            $slide->save();

            if(settings('translations'))
            {
                $set_languages = $this->get_set_languages();

                // Update Any Existing Pivots...
                $existing_trans = $slide->translations;

                if(!$existing_trans->isEmpty())
                {
                    foreach ($set_languages->languages_array as $language)
                    {
                        $line1 = $request->input('text_line1_'.$language.'');
                        $line2 = $request->input('text_line2_'.$language.'');

                        foreach($existing_trans as $trans)
                        {
                            // Find The Trans
                            $update_trans = Translation::where('id', $trans->id)->where('language', $language)->first();

                            if($update_trans)
                            {
                                // Update...
                                $save_trans = Translation::find($update_trans->id);
                                $save_trans->text_line1 = $line1;
                                $save_trans->text_line2 = $line2;
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
                        $line1 = $request->input('text_line1_' . $language . '');
                        $line2 = $request->input('text_line2_' . $language . '');

                        if (!in_array($language, $already_set))
                        {
                            // Create New Translation
                            if(isset($line1) || isset($line2))
                            {
                                // Create The Translation....
                                $translations = array(
                                    'text_line1'            => $line1,
                                    'text_line2'            => $line2,
                                    'language'              => $language,
                                    'translationable_id'    => $slide->id,
                                    'translationable_type'  => 'App\Slide'
                                );

                                $slide->translations()->create($translations);
                            }
                        }
                    }
                }
                else
                {
                    // Create New Translation...
                    foreach($set_languages->languages_array as $language)
                    {
                        $line1 = $request->input('text_line1_'.$language.'');
                        $line2 = $request->input('text_line2_'.$language.'');

                        // Create New Translation
                        if(isset($line1) || isset($line2))
                        {
                            // Create The Translation....
                            $translations = array(
                                'text_line1'            => $line1,
                                'text_line2'            => $line2,
                                'language'              => $language,
                                'translationable_id'    => $slide->id,
                                'translationable_type'  => 'App\Slide'
                            );

                            $slide->translations()->create($translations);
                        }
                    }
                }
            }

            // redirect
            $request->session()->flash('message_success', 'Successfully updated slide!');
            return redirect('admin/slides/'.$slide->id.'/edit');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete
        $slide = Slide::find($id);

        if($slide)
        {
            $thumb_path = 'slides/thumbs/'.basename($slide->photo);
            Storage::delete($slide->photo);
            Storage::delete($thumb_path);

            $slide->delete();

            // Send Confirmed Response...
            return response()->json([
                'error' => 'false',
                'redirect' => '/admin/slides',
                'message' => 'Slide Deleted!'
            ]);

        }
    }

    public function delete($id)
    {
        // delete
        $slide = Slide::find($id);

        $thumb_path = 'slides/thumbs/'.basename($slide->photo);
        Storage::delete($slide->photo);
        Storage::delete($thumb_path);
        $slide->delete();

        $data = ['success' => 'Successfully deleted the slide!'];
        return redirect(admin_url('slides/'))->with($data);
    }

}
