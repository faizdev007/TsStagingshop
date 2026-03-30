<?php

namespace App\Http\Controllers\Backend;

use App\FooterBlock;
use App\FooterLink;
use App\Page;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FooterBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $blocks = FooterBlock::orderBy('created_at', 'desc')
            ->when($request->input('q'), function($query) use ($request)
            {
                return $query->where(function ($query) use ($request)
                {
                    $query->where('footer_block_title', 'like', '%'.$request->input('q').'%');
                });
            })
            ->paginate(20);

            return view('backend.footer_blocks.index', [
                'blocks' => $blocks,
                'pageTitle' => 'Footer Blocks'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Can Only have (4) Footer Blocks...
        $num_footer_blocks = FooterBlock::all()->count();

        if($num_footer_blocks == 4)
        {
            // Can't create anymore
            return redirect(admin_url('footer-blocks'))->with('error','You can only have 4 footer blocks');
        }
        else
        {
            return view('backend.footer_blocks.create',
                [
                    'pageTitle' => 'Create Footer Block'
                ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Create The Block....
        $footer_block = new FooterBlock;
        $footer_block->footer_block_title = $request->input('title');
        $footer_block->save();

        return redirect(admin_url('footer-blocks/'.$footer_block->footer_block_id.'/edit'))->with(
            [
                'success' => 'Block Created'
            ]
        );
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('backend.footer_blocks.edit',
            [
                'pageTitle' => 'Edit Footer Block',
                'item' => FooterBlock::find($id)
            ]
        );
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
        $footer_block = FooterBlock::find($id);
        $footer_block->footer_block_title = $request->input('title');
        $footer_block->save();

        // redirect
        $request->session()->flash('message_success', 'Successfully updated block!');
        return redirect('admin/footer-blocks/'.$id.'/edit');
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
        $footer_block = FooterBlock::find($id);
        $footer_block->links()->delete();
        $footer_block->delete();

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Block Deleted!'
        ]);
    }

    public function blocks_sort(Request $request)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id)
        {
            $link = FooterBlock::find($id);
            $link->footer_block_order = $order;
            $link->save();
        }

        return response()->json(
            [
                'message' => 'Reordering Successful'
            ]
        );
    }

    public function links_create()
    {
        return view('backend.footer_blocks.links.create',
            [
                'pageTitle'    =>  'Create New Link',
                'pages'        =>  Page::all(),
            ]);
    }

    public function links_store(Request $request)
    {

        // validate
        $validator = Validator::make($request->all(), [
            'title'                 => 'required|max:80',
            'footer_link_url'       => 'array',
        ]);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        else
        {
            if(empty($footer_link_url))
            {
                // Footer Link URL not being validated by View, Handle in Controller...

                $validator = Validator::make([], []); // Empty data and rules fields
                $validator->errors()->add('footer_link_url', 'The footer link URL field is required');
                throw new ValidationException($validator);
            }
            else
            {
                $url_type = $request->input('url_type');

                // Create Footer Link....
                $footer_link = new FooterLink;
                $footer_link->footer_link_title = $request->input('title');

                // Footer Link Type...
                if($url_type == 'custom-link')
                {
                    $footer_link_url = $request->input('footer_link_url')[1];
                }
                else
                {
                    $footer_link_url = $request->input('footer_link_url')[0];
                }

                $footer_link->footer_link_url = $footer_link_url;
                $footer_link->footer_link_type = $url_type;
                $footer_link->footer_block_id = $request->input('footer_block_id');
                $footer_link->save();

                // redirect
                $request->session()->flash('message_success', 'Successfully created link!');
                return redirect('admin/footer-blocks/'.$request->input('footer_block_id').'/edit');
            }

        }

    }

    public function links_edit($id)
    {
        return view('backend.footer_blocks.links.edit', [
            'pageTitle' => 'Edit Link',
            'item'      => FooterLink::find($id),
            'pages'     =>  Page::all(),
        ]);
    }

    public function links_destroy($id)
    {
        // delete
        $footer_block = FooterLink::find($id);
        $footer_block->delete();

        // Send Confirmed Response...
        return response()->json([
            'error' => 'false',
            'message' => 'Link Deleted!'
        ]);
    }

    public function links_update(Request $request, $id)
    {
        $url_type = $request->input('url_type');

        $footer_link = FooterLink::find($id);
        $footer_link->footer_link_title = $request->input('title');

        // Footer Link Type...
        if($url_type == 'custom-link')
        {
            $footer_link_url = $request->input('footer_link_url')[1];
        }
        else
        {
            $footer_link_url = $request->input('footer_link_url')[0];
        }

        $footer_link->footer_link_url = $footer_link_url;
        $footer_link->footer_link_type = $url_type;
        $footer_link->save();

        // redirect
        $request->session()->flash('message_success', 'Successfully created link!');
        return redirect('admin/footer-blocks/'.$footer_link->footer_block_id.'/edit');

    }

    public function links_sort(Request $request)
    {
        $items =  $request->input("item");

        foreach($items as $order => $id)
        {
            $link = FooterLink::find($id);
            $link->footer_link_order = $order;
            $link->save();
        }

        return response()->json(
            [
                'message' => 'Reordering Successful'
            ]
        );
    }
}
