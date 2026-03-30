<?php

namespace App\Http\Controllers\Frontend;

use App\Enquiry;
use App\Models\LeadAutomation;
use App\Models\Message;
use App\Models\SaveSearch;
use App\PropertyAlert;
use App\Shortlist;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    private $user_id;

    public function __construct()
    {
        $this->middleware(function ($request, $next)
        {
            if(Auth::user())
            {
                $this->user_id = Auth::user()->id;

                return $next($request);
            }
            else
            {
                return redirect('login');
            }
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // If Property ID Is Stored, Save it in the users' shortlists table...
        $user = User::find($this->user_id);

        if ($request->session()->has('property_id'))
        {
            //$user = User::find($this->user_id);

            $property_id = $request->session()->get('property_id');

            $shortlist = new Shortlist();

            //CHECK IF EXIST
            $check_exist = $shortlist->check_exist($property_id);

            // See if a Lead Already Exists...
            $lead_exists = LeadAutomation::where('lead_type', 'shortlist')
                ->where('lead_is_subscribed', 'y')
                ->where('user_id', $this->user_id)
                ->first();

            if(!$check_exist)
            {
                // Add to Shortlist....
                $shortlist->add($property_id, $this->user_id);

                if(!$lead_exists)
                {
                    // Create a new lead Category = Property Shortlist...
                    $lead = new Enquiry;
                    $lead->category = 'Automated';
                    $lead->name = $user->name;
                    $lead->email = $user->email;
                    $lead->telephone = $user->telephone;
                    $lead->message = "System Automated Shortlist message";
                    $lead->url = request()->headers->get('referer');
                    $lead->read_at = Carbon::now();
                    $lead->save();

                    // Make Lead Automation...
                    $lead_automation = new LeadAutomation;
                    $lead_automation->lead_id = $lead->id;
                    $lead_automation->lead_type = "shortlist";
                    $lead_automation->lead_is_subscribed = 'y';
                    $lead_automation->lead_contact_type = 'email';
                    $lead_automation->last_contacted = Carbon::now();
                    $lead_automation->user_id = $user->id;
                    $lead_automation->save();
                }
            }

            // Delete from Shortlist...
            $request->session()->forget('property_id');
        }

        $data = array
        (
            'shortlist' => Shortlist::where('user_id', $this->user_id)->with('property')->orderBy('created_at', 'desc')->get(),
            'alerts'    => PropertyAlert::where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get(),
            'searches'  => SaveSearch::where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get(),
            'user' => $user
        );

        // Bespoke Account page per Demo...?
        if (view()->exists('frontend.demo1.account.index'))
        {
            return view('frontend.demo1.account.index', $data);
        }
        else
        {
            return view('frontend.shared.account.index', $data);
        }

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
        // Validate...
        if($request->input('password') !== NULL)
        {
            $validator = Validator::make($request->all(), [
                //'name'                  => 'required|max:80',
                'email'                 => 'required',
                'first_name'              => 'required|max:80',
                'last_name'              => 'required|max:80',
                'telephone'             => '',
                'password'              => 'required|confirmed|min:8|max:190'
            ]);
        }
        else
        {
            $validator = Validator::make($request->all(), [
                'first_name'              => 'required|max:80',
                'last_name'              => 'required|max:80',
                'email'             => 'required',
                'telephone'         => ''
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
            // Update The User...
            $user = User::find($id);
            $user->name = $request->input('first_name').' '.$request->input('last_name');
            $user->firstname = $request->input('first_name');
            $user->lastname = $request->input('last_name');

            $user->email = $request->input('email');
            $user->telephone = $request->input('telephone');

            if($request->input('password') !== NULL)
            {
                // Update Password....
                $user->password = Hash::make($request->input('password'));
            }

            $user->save();

            // Confirm Registration (Show Flash)...
            $request->session()->flash('message_success', 'Details Updated!');

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
        $user = User::find($id);

        if($user)
        {
            Auth::logout();

            // Delete User...
            $user->delete();

            // Redirect to account with message - Deleted...
            $request->session()->flash('message_success', 'Account Deleted!');

            return response()->json(
                [
                    'message' => 'Account Deleted!',
                    'url'     => '/account/'
                ]
            );
        }
        else
        {
            return false;
        }

    }
}
