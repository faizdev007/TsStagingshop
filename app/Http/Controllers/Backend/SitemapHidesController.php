<?php

namespace App\Http\Controllers\Backend;

use App\Models\SitemapHides;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use Validator;

class SitemapHidesController extends Controller
{
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
        $items = SitemapHides::orderBy('id', 'DESC')

        // filter other data
        ->when($request->input('q'), function($query) use ($request){
            return $query->where(function ($query) use ($request) {
                $query->where('url', 'like', '%'.$request->input('q').'%');
            });
          })
        ->paginate(20);

        $data = array(
          'pageTitle'=>'Sitemap Hides',
          'items'=> $items,
          'request'=> $request,
        );
        return view('backend.sitemap_hides.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
          'pageTitle'=>'Add URL'
        );

        return view('backend.sitemap_hides.create')->with($data);
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
            'url'       => 'required|max:255',
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

            return redirect('admin/sitemap_hides/create')
                ->withInput($request->all());
        }
        else
        {
            // store
            $item = new SitemapHides;
            $item->url = $request->input('url');
            $item->save();

            // redirect
            $request->session()->flash('message_success', 'Successfully added URL!');
            return redirect('admin/sitemap_hides/'.$item->id.'/edit');
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
        // get the item
        $item = SitemapHides::find($id);

        $data = array(
          'pageTitle'   => 'Edit URL',
          'item'       => $item,
        );

        return view('backend.sitemap_hides.edit')->with($data);
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
            'url'       => 'required|max:255',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }
            $request->session()->flash('message_danger', $error_txt);

            return redirect('admin/sitemap_hides/'.$id.'/edit')
                ->withInput($request->all());
        } else {

            // update
            $item = SitemapHides::find($id);
            $item->url = $request->input('url');
            $item->save();

            // redirect
            $request->session()->flash('message_success', 'Successfully updated URL!');
            return redirect('admin/sitemap_hides/'.$item->id.'/edit');
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
        $item = SitemapHides::find($id);

        if($item)
        {
            $item->delete();

            // Send Confirmed Response...
            return response()->json([
                'error' => 'false',
                'redirect' => '/admin/sitemap_hides',
                'message' => 'Item Deleted!'
            ]);

        }
    }
}
