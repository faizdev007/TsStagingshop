<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Metadata;
use Illuminate\Support\Str;
use Validator;

class MetadataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Metadata::orderBy('title', 'ASC')->paginate(20);

        $data = array(
          'pageTitle'=>'Metadata',
          'items'=> $items
        );
        return view('backend.metadata.index')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = array(
          'pageTitle'=>'Create Metadata'
        );
        return view('backend.metadata.create')->with($data);
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
            'url'       => 'required|max:1000',
            'title'     => 'required|max:255',
        );
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message) {
                $error_txt .= "$message ";
            }
            $request->session()->flash('message_danger', $error_txt);

            return redirect('admin/metadata/create')
                ->withInput($request->all());
        } else {

            // trim the url first
            $url = $request->input('url');
            $url = rtrim($url, '/');
            $url = str_replace('https://', '', $url);
            $url = str_replace('http://', '', $url);
            $url = str_replace('www.', '', $url);

            // store
            $metadata = new Metadata;
            $metadata->url = $url;
            $metadata->title = $request->input('title');
            $metadata->description = $request->input('description');
            $metadata->save();

            // redirect
            $request->session()->flash('message_success', 'Successfully created metadata!');
            return redirect('admin/metadata/'.$metadata->id.'/edit');
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
        $metadata = Metadata::find($id);
        $tab = $request->input('tab');

        $data = array(
          'pageTitle'   =>  'Edit Metadata',
          'metadata'       =>  $metadata,
        );

        return view('backend.metadata.edit')->with($data);
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
            'url'       => 'required|max:1000',
            'title'     => 'required|max:255',
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

            return redirect('admin/metadata/'.$id.'/edit')
                ->withInput($request->all());
        } else {

            // trim the url first
            $url = $request->input('url');
            $url = rtrim($url, '/');
            $url = str_replace('https://', '', $url);
            $url = str_replace('http://', '', $url);
            $url = str_replace('www.', '', $url);

            // store
            $metadata = Metadata::find($id);
            $metadata->url = $url;
            $metadata->title = $request->input('title');
            $metadata->description = $request->input('description');
            $metadata->save();

            // redirect
            $request->session()->flash('message_success', 'Successfully updated metadata!');
            return redirect('admin/metadata/'.$metadata->id.'/edit');
        }
    }

    public function delete($id)
    {
        // delete
        $metadata = Metadata::find($id);
        $metadata->delete();

        $data = ['success' => 'Successfully deleted the metadata!'];
        return redirect(admin_url('metadata/'))->with($data);
    }

}
