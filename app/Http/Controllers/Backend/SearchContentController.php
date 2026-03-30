<?php

namespace App\Http\Controllers\Backend;

use App\SearchContent;
use App\SearchContentBlock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SearchContentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'super']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_content = SearchContent::orderBy('created_at', 'desc')
            ->when($request->input('q'), function($query) use ($request)
            {
                return $query->where(function ($query) use ($request)
                {
                    $query->where('content_title', 'like', '%'.$request->input('q').'%');
                });
            })
            ->paginate(20);

        return view('backend.search_content.index', [
            'pageTitle' => 'Search Content',
            'content'   => $search_content
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->role_id != '1' )
        {
            return redirect(admin_url(''));
        }

        return view('backend.search_content.create',[
            'pageTitle' => 'Create Search Content'
        ]);
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
        $validator = Validator::make($request->all(), [
            // 'content_title'     => 'required|max:80',
            'content_title'     => 'required',
            'content_url'       => 'required',
            'content'           => 'required'
        ]);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            $block_data = $request->block;

            if(!empty($block_data))
            {
                // Make Search Content Blocks....
                $count = 1; // Count = 1 as tinyMCE would not allow a pecuilar input name...
                foreach($block_data as $block_data)
                {
                    $content = $request->input('content-'.$count);

                    if(empty($content))
                    {
                        $validator = Validator::make([], []); // Empty data and rules fields
                        $validator->errors()->add('content-'.$count, 'The content field is required');
                        throw new ValidationException($validator);
                    }
                    else
                    {
                        if($count == 1)
                        {
                            // Make Search Content...
                            $search_content = new SearchContent;
                            $search_content->content_title = $request->content_title;
                            $search_content->content_url = $request->content_url;
                            $search_content->content = $request->input('content');
                            $search_content->save();
                        }

                        $content_block = new SearchContentBlock;
                        $content_block->search_content_id = $search_content->id;
                        $content_block->heading = $block_data['heading'];
                        $content_block->content = $content;
                        $content_block->language = 'english'; //$block_data['language'];
                        $content_block->save();

                        $count++;
                    }


                }
            }
            else
            {
                // Make Search Content (No Blocks Created)...
                $search_content = new SearchContent;
                $search_content->content_title = $request->content_title;
                $search_content->content_url = $request->content_url;
                $search_content->content = $request->input('content');
                $search_content->save();
            }

            return redirect(admin_url('search-content/'.$search_content->id.'/edit'))->with(
                [
                    'success' => 'Item Created'
                ]
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = SearchContent::findOrFail($id);

        return view('backend.search_content.edit', [
            'item' => $item,
            'languages' => config('custom.languages'),
            'pageTitle' => 'Edit - '. $item->content_title
        ]);
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
        $validator = Validator::make($request->all(), [
            // 'content_title'     => 'required|max:80',
            'content_title'     => 'required',
            'content_url'       => 'required',
            'content'           => 'required'
        ]);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            $block_data = $request->block;

            if(!empty($block_data))
            {

                // Wipe Out Existing Content Blocks...
                DB::table('search_content_blocks')->where('search_content_id', $id)->delete();

                $count = 1; // Count = 1 as tinyMCE would not allow a pecuilar input name...

                foreach($block_data as $k => $v)
                {
                    $content = $request->input('content-'.$count);
                    $edit_content = $request->input('content-'.$k);

                    if(empty($content) && empty($edit_content))
                    {
                        $validator = Validator::make([], []); // Empty data and rules fields
                        $validator->errors()->add('content-'.$count, 'The content field is required');
                        throw new ValidationException($validator);
                    }
                    else
                    {
                        // Create new Block...
                        $content_block = new SearchContentBlock;
                        $content_block->search_content_id = $id;
                        $content_block->heading = $v['heading'];
                        $content_block->content = $request->input('content-'.$k);
                        $content_block->language = 'english'; //$block_data['language'];
                        $content_block->save();
                    }

                    if($count == 1)
                    {

                    }

                    $count++;
                }
            }

            return redirect(admin_url('search-content/'.$id.'/edit'))->with(
                [
                    'success' => 'Item Updated'
                ]
            );
        }

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
        $search_content = SearchContent::find($id);
        $search_content->blocks()->delete();
        $search_content->delete();

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Content Deleted!'
        ]);
    }

    public function destroy_block($id)
    {
        // Delete Block...
        $block = SearchContentBlock::find($id);
        $block->delete();

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Block Deleted!'
        ]);
    }

    /**
     * May Move this to a Controller called Languages... SB 13/5
     */

    public function get_languages()
    {
        $languages = config('custom.languages');

        $json_data['results'] = array();

        $count = 0;
        foreach($languages as $key => $val)
        {
            $json_data['results'][$count]['id'] = $key;
            $json_data['results'][$count]['text'] = $val;

            $count++;
        }

        return response()->json($json_data);
    }
}
