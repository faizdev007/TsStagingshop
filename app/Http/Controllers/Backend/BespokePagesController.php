<?php

namespace App\Http\Controllers\Backend;
use App\Models\PageSection;
use App\Models\SectionContent;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Image;

class BespokePagesController extends Controller
{
    private $is_set;

    public function __construct()
    {
        $this->is_set = settings('bespoke_pages');

        // Prevent Access if Turned Of....
        if($this->is_set == '0')
        {
            return redirect('/admin')->send();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pages = Page::where('page_type', 'bespoke')
            // filter other data
            ->when($request->input('q'), function($query) use ($request)
            {
                return $query->where(function ($query) use ($request) {
                    $query->where('title', 'like', '%'.$request->input('q').'%')
                        ->orWhere('content', 'like', '%'.$request->input('q').'%');
                });
            })
            ->paginate(20);

        return view('backend.bespoke_pages.index', [
            'pages'     => $pages,
            'pageTitle' => 'Bespoke Pages',
            'request'   => $request
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $page = Page::find($id);
        $tab = $request->input('tab');

        return view('backend.bespoke_pages.edit',
        [
            'pageTitle'     =>  'Edit Page',
            'page'          =>  $page,
            'sections'      =>  config('custom.sections'),
            'tab'           =>  $request->input('tab')
        ]);
    }

    public function create_section(Request $request)
    {
        // Section Type...
        $section_type = $request->input('section_type');

        // Create The Section...
        $section = new PageSection;
        $section->page_id = $request->input('page_id');
        $section->title = ucfirst(str_replace('_', ' ', $request->input('section_type')));
        $section->type = $section_type;
        $section->content = '';
        $section->save();

        $request->session()->flash('message_success', 'Successfully created section!');

        if($section_type == 'latest_properties')
        {
            // Go Back A Page...
            return redirect()->back();
        }
        else
        {
            // Now Redirect To Section Edit...
            return redirect('admin/bespoke-section/'.$section->id.'/edit');
        }
    }

    public function edit_section($id)
    {
        // Find The Section..
        $section = PageSection::find($id);

        // Contents
        if($section->section_contents->count() > 0)
        {
            $collection = $section->section_contents;
            $section_contents = $collection->pluck('page_id');
            $section_contents->all();
            $section_contents->toArray();
        }
        else
        {
            $section_contents = '';
        }

        return view('backend.bespoke_pages.sections.edit',
            [
                'pages'             => Page::where('page_type', 'bespoke')->where('id', '!=' , $section->page->id)->get(),
                'pageTitle'         => 'Edit Component - '. ucfirst($section->title),
                'section'           => $section,
                'section_contents'  => $section_contents
            ]);
    }

    public function update_section($id, Request $request)
    {
        $section_type = $request->input('type');

        // Do Validation (Per Section *sic*)...
        if($section_type == 'text_block')
        {
            $validator = Validator::make($request->all(), [
                'title'                 => 'required|max:80',
                'content'               => 'required',
            ]);
        }

        if($section_type == 'news')
        {
            $validator = Validator::make($request->all(), [
                'title'                 => 'required|max:80'
            ]);
        }

        if($section_type == 'popular_locations')
        {
            $validator = Validator::make($request->all(), [
                'title'                => 'required|max:80',
                'section_content'      => 'required'
            ]);
        }

        if($section_type == 'local_information' || $section_type == 'things_to_do')
        {
            $validator = Validator::make($request->all(), [
                'section_title'                 => 'required|max:80',
                'section_content'               => 'required',
            ]);
        }

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            // Create / Update Section Content, If Needed...
            if($section_type == 'local_information' || $section_type == 'popular_locations' || $section_type == 'things_to_do')
            {
                if($section_type == 'popular_locations')
                {
                    // Create Section Content...
                    $section_content = new SectionContent;
                    $section_content->title = $request->input('section_title');
                    $section_content->content = $request->input('section_content');
                    $section_content-> url = $request->input('url');
                    $section_content->section_id = $id;
                    $section_content->type = $section_type;

                    $section_content->save();
                }

                if($section_type == 'local_information' || $section_type == 'things_to_do')
                {
                    // Create New Section Content Block...
                    $section_content = new SectionContent;
                    $section_content->title = $request->input('section_title');
                    $section_content->content = $request->input('section_content');
                    $section_content->section_id = $id;
                    $section_content->type = $section_type;

                    $section_content->save();
                }
            }

            // Update The Section...
            $section = PageSection::find($id);
            $section->title = $request->input('title');
            $section->content = $request->input('content');
            $section->save();

            $request->session()->flash('message_success', 'Successfully added component!');

            if($section_type == 'local_information' || $section_type == 'things_to_do' || $section_type == 'popular_locations')
            {
                return redirect('/admin/bespoke-section/'.$id.'/edit');
            }
            else
            {
                // Go Back To Edit...
                return redirect('/admin/bespoke-pages/'.$section->page_id.'/edit?tab=components');
            }
        }
    }

    public function add_image(Request $request, $id)
    {
        // Validate
        $rules = array(
            'file' => 'required|max:10240|mimes:jpeg,png,gif,tif',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
        {
            $errors = $validator->errors();
            $error_txt = false;

            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }

            $request->session()->flash('message_danger', $error_txt);

        }
        else
        {

            $path = $request->file('file')->store('pages/bespoke');

            if($path)
            {
                // store file to db
                $content = SectionContent::find($id);

                // remove existing photo
                if(!empty($content->image))
                {
                    $thumb_path = $content->image;
                    Storage::delete($content->image);
                }

                // update photo
                $content->image = $path;
                $content->save();

                $img = Image::make(storage_path('app/public/pages/bespoke/'.basename($path)));
                $img->fit(400, 400);
                $img->save(storage_path('app/public/pages/bespoke/'.basename($path)));

                $request->session()->flash('message_success', 'Successfully uploaded image!');

            }
            else
            {
                $request->session()->flash('message_danger', 'Error uploaded photo!');
            }
        }

        return 'completed';
    }

    public function update_section_content($id, Request $request)
    {
        $tab = $request->input('tab');

        return view('backend.bespoke_pages.content.edit', [
            'content'   => SectionContent::find($id),
            'pageTitle' => 'Edit Content',
            'tab'       => $tab
        ]);
    }

    public function destroy_section($id)
    {
        $page_section = PageSection::find($id);

        if($page_section)
        {
            $destroy = $page_section->delete();
        }

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Component Deleted!'
        ]);
    }

    public function destroy_content($id)
    {
        $content = SectionContent::find($id);

        if($content)
        {
            $destroy = $content->delete();
        }

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Content Deleted!'
        ]);
    }

    public function store_section_content(Request $request, $id)
    {
        // Find The Section Content...
        $section_content = SectionContent::find($id);
        $section_content->title = $request->input('title');
        $section_content->content = $request->input('content');

        if($request->has('url'))
        {
            $section_content->url = $request->input('url');
        }

        $section_content->save();

        $request->session()->flash('message_success', 'Successfully updated content!');

        // Go Back A Page...
        return redirect()->back();
    }

    public function section_sort(Request $request)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id)
        {
            $section = PageSection::find($id);
            $section->order = $order;
            $section->save();
        }

        return response()->json(
            [
                'message' => 'Reordering Successful'
            ]
        );
    }

    public function content_sort(Request $request, $id)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id)
        {
            $section_content = SectionContent::find($id);
            $section_content->order = $order;
            $section_content->save();
        }

        return response()->json(
            [
                'message' => 'Reordering Successful'
            ]
        );
    }

    public function delete_photo(Request $request, $id)
    {
        $content = SectionContent::find($id);

        if(!empty($content))
        {
            $thumb_path = $content->image;
            Storage::delete($content->image);
            $content->image = '';
            $content->save();
            $request->session()->flash('message_success', 'Successfully deleted photo!');
        }

        return response()->json([
            'error' => 'false',
            'message' => 'Image Deleted!'
        ]);
    }
}
