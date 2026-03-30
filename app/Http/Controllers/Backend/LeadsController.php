<?php

namespace App\Http\Controllers\Backend;

use App\Enquiry;
use App\Mail\PropertyAlertCronMail;
use App\Mail\PropertySearchMail;
use App\Mail\PropertyUpdated;
use App\Models\LeadAutomation;
use App\Models\SaveSearch;
use App\Property;
use App\PropertyAlert;
use App\Shortlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class LeadsController extends Controller
{

    public function __construct()
    {
        $this->moduleTitle = "Leads";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Set Lead Into Automation
     */

    public function create_automation(Request $request, $lead_id, $type)
    {
        // Get The Existing Lead...
        $lead = Enquiry::find($lead_id);

        // Create The Automation....
        $automation = new LeadAutomation;
        $automation->lead_id = $lead_id;
        $automation->lead_type = $type;
        $automation->lead_contact_type = 'email'; // Default For Now....
        $automation->last_contacted = Carbon::now();
        $automation->save();

        // Enrolled, Let Admin User Know....
        $request->session()->flash('message_success', 'Successfully enrolled user to Automated Emails!');
        return redirect('admin/enquiries/'.$lead_id.'/edit');

    }

    public function cancel_automation(Request $request, $lead_id)
    {
        $automation = LeadAutomation::where('lead_id', $lead_id)->first();
        $automation->lead_is_subscribed = 'n';
        $automation->save();

        // Enrolled, Let Admin User Know....
        $request->session()->flash('message_success', 'Successfully removed user from Automated Emails!');

        return redirect($request->server('HTTP_REFERER'));

    }

    public function get_email_clicks($id)
    {
          // Didnt Need to make an Eloquent Model for this, So used DB:: Query Builder instead
          $data = DB::table('sent_emails_url_clicked')->where('sent_email_id', $id)->get();

          // Send Confirmed Response...
          return response()->json(
            [
                'error' => 'false',
                'message' => 'Hello!',
                'data'    => $data
            ]
          );
    }
}
