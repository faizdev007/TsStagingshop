<?php

namespace App\Http\Controllers\Backend;

use App\Models\LeadAutomation;
use Config;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Mail\EnquiryMail;
use App\Mail\GenericMail;
use App\Enquiry;
use App\Jobs\SendEnquiryEmail;
use App\Property;
use App\User;
use Validator;
use Carbon\Carbon;
use DB;

class EnquiriesController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // List categories based on enquiries
        $categories = Enquiry::select('category')->orderBy('category')->distinct()->pluck('category')->toArray();
        $categoriesData = [];
        $categoriesVal = [];

        foreach($categories as $key => $val) {
            if($val == 'Property Enquiry (Sidebar)'){
                $categoriesData['Property Enquiry'] = 'Property Enquiry';  // Add the main Property Enquiry
            }
            $categoriesData[$val] = $val;
        }
        //sort($categoriesData);

        $enquiry = new Enquiry();
        $enquiries = $enquiry->search($request);
        $data = array(
          'pageTitle'=>'Enquiries',
          'enquiries'=> $enquiries,
          'request'=> $request,
          'categories'=> $categoriesData,
        );

        // Add the new filter
        // Add the e_status filter
        // if ($request->has('e_status')) {
        //     $query->where('e_status', $request->input('e_status'));
        // }

        return view('backend.enquiries.index')->with($data);
        

    
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $is_agent = (Auth::user()->role_id == 3) ? 1 : '';

        $enquiry = Enquiry::where('id', $id)
        // FILTER for agent USERS
        ->when($is_agent, function($query){
            $query->whereHas('property', function($query){
                $query->whereUserId(Auth::user()->id);
            });
        })
        ->first();

        // REDIRECT IF EMPTY
        if(empty($enquiry)){ return redirect(admin_url('enquiries')); }

        if(empty($enquiry->read_at)){
            $enquiry->read_at = Carbon::now();
            $enquiry->save();
        }

        $data = array(
          'pageTitle'   =>  'Enquiry',
          'enquiry'       =>  $enquiry,
        );

        return view('backend.enquiries.edit')->with($data);
    }

/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function addnoteUpdate(Request $request, $id)
    {
        $enquiry = Enquiry::find($id);

        $enquiry->add_notes =  $request->input('add_notes');
        $enquiry->e_status =  $request->input('e_status');
        $enquiry->save();

       // Set a flash message
       $request->session()->flash('message_success', 'Notes and Status Saved Successfully!');

    

    // Redirect to the home page with a message
    return redirect('admin/enquiries/'.$id.'/edit');
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
        'reply_message'  => 'required',
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

        return redirect('admin/enquiries/'.$id.'/edit');

    } else {
        // store
        $enquiry = Enquiry::find($id);
        $enquiry->reply_message = $request->input('reply_message');
        $enquiry->replied_at = Carbon::now();
        $enquiry->save();

        //Notify reply
        if( !empty($enquiry->reply_message) ){
            //User email
            $message = [];
            $message['sub'] = 'Reply to your enquiry on '.config('app_settings.website.name');
            $message['msg'] = $enquiry->reply_message;
            SendEnquiryEmail::dispatch(null,$message,$enquiry->email);
        }

        // enquiry
        $request->session()->flash('message_success', 'Successfully sent reply!');

        return back();
    }
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
        $enquiry = Enquiry::find($id);

        if($enquiry)
        {
            $enquiry->delete();

            // See If Enquiry has any leads (Will need to delete them too)...
            $leads = $enquiry->lead_emails;

            if($leads)
            {
                // Delete Leads...
                $lead_email = LeadAutomation::where('lead_id', $enquiry->lead_emails->lead_id)->first();
                $lead_email->delete();
            }

            // Send Confirmed Response...
            return response()->json([
                'error' => 'false',
                'message' => 'Enquiry Deleted!'
            ]);

        }

    }


    /**
     * Delete a lead
     */
    public function delete($id)
    {
        $enquiry = Enquiry::find($id);
        if(empty($enquiry)){ return redirect(admin_url('enquiries')); }
        $enquiry->delete();
        $data = ['success' => 'Successfully deleted enquiry!'];

        return redirect(admin_url('enquiries'))->with($data);
    }


    /**
     * Archive a lead
     */
    public function archive($id)
    {
        $enquiry = Enquiry::find($id);
        if(empty($enquiry)){ return redirect(admin_url('enquiries')); }
        $enquiry->archived_at = Carbon::now()->format('Y-m-d H:i:s');
        $enquiry->save();
        $data = ['success' => 'Enquiry has been archived.'];

        return redirect(admin_url('enquiries'))->with($data);
    }

    /**
     * Reactivate a lead
     */
    public function reactivate($id)
    {
        $enquiry = Enquiry::find($id);
        if(empty($enquiry)){ return redirect(admin_url('enquiries')); }
        $enquiry->archived_at = NULL;
        $enquiry->save();
        $data = ['success' => 'Enquiry has been reactivated.'];

        $url = url()->previous();
        return redirect($url)->with($data);
    }

    /**
     * This will export enquiries in CSV.
     *
    */

    public function download(Request $request)
    {
      app('debugbar')->disable();

        if( count($request->all()) ){
            $enquiry = new Enquiry();
            $enquiries = $enquiry->search($request, NULL);
        }else{
            $is_agent = (Auth::user()->role_id == 3) ? 1 : '';
            $enquiries = Enquiry::orderBy('created_at', 'DESC')
                            // Filter for agent USERS
                            ->when($is_agent, function($query) use ($request){
                                $query->whereHas('property', function($query){
                                    $query->whereUserId(Auth::user()->id);
                                });
                            })->paginate(100); //memory limits
        }

        $filename = 'download_enquiries_'.date ('YmdHis');

        header ("Content-type: text/csv");
        header ("Content-Disposition: attachment; filename={$filename}.csv");
        header ("Pragma: no-cache");
        header ("Expires: 0");

        $ctr=0;
        $csv[$ctr] = ['ID','Category','Agent','Property Ref','Email','Telephone','From','Message','Date'];

        foreach($enquiries as $enquiry){
            $ctr++;

            /*
            $property = Property::where('ref', $enquiry->ref)->first();
            $agent = !empty($property) ? User::where('id', $property->user_id)->first() : false;
            $agent_name = !empty($agent) ? $agent->first_name.' '.$agent->last_name : false;
            */

            $csv[$ctr] = [ $enquiry->id,
                           $enquiry->category,
                           //$agent_name,
                           !empty($enquiry->property->user) ? $enquiry->property->user->name : false,
                           $enquiry->ref,
                           $enquiry->email,
                           $enquiry->telephone,
                           $enquiry->url,
                           $enquiry->message,
                           $enquiry->display_date];
        }
        foreach ($csv as $line) {
            echo '"'.implode('","',$line).'"'.PHP_EOL;
        }



    }

    public function selectionNoteUpdate(Request $request){
        foreach($request->es as $single){
            
            $enquiry = Enquiry::find($single);
    
            $enquiry->add_notes =  $request->input('add_notes');
            $enquiry->e_status =  $request->input('m_states');
            $enquiry->save();
        }

        // Set a flash message
        $request->session()->flash('message_success', 'Notes and Status Saved Successfully!');

        return back();
    }
}
