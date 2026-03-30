<?php

namespace App\Http\Controllers\Frontend;

use App\Models\DevelopmentUnit;
use App\Property;
use App\User;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use NZTim\Mailchimp\Mailchimp;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnquiryMail;
use App\Mail\GenericMail;
use App\Enquiry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendAdminEmail;
use App\Jobs\SendEnquiryEmail;
use App\Jobs\SendSubscribeEmail;
use App\Traits\PropertyBaseTrait;
use App\Mail\SubscribeMail;
use App\Subscriber;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator as FacadesValidator;
use Illuminate\Validation\ValidationException;
use libphonenumber\PhoneNumberUtil;
use Psy\Readline\Hoa\Console;

class EnquiriesController extends Controller
{
    private $form_category;
    private $captcha_enabled;
    use PropertyBaseTrait;

    /**
     * Instantiate a new UserController instance.
     * Assuming only Ajax function use this controller
     */




public function insertClick(Request $request)
{
    // Validate and insert data into the table
    $ref = $request->input('h1value'); // Get the value from the 'h1value' input field
    $p_name = $request->input('h2value');
    $clicked_at = now(); // or any timestamp you prefer

    // Insert data into the table using Laravel's DB query builder
    DB::table('whatsapp_clicks')->insert([
        'ref' => $ref,
        'p_name' => $p_name,
        'clicked_at' => $clicked_at,
    ]);

    // You can add a success message or redirect to another page if needed
    return redirect()->back()->with('success', 'Click data inserted successfully.');
}



    public function __construct()
    {
        $this->captcha_enabled = settings('recaptcha_enabled');

        if(empty($_SERVER['HTTP_X_REQUESTED_WITH']))
        {
           //abort(404);
        }
    }

    // One Enquiry Function Do Not Reepeat Yourself (DRY)....






    public function enquiry(Request $request,?bool $ref = false)
    {
        $util = PhoneNumberUtil::getInstance();
        $number = $util->parse($request->telephone, null);

        $rules = array(
            'email' => 'required|email',
            'telephone' => ['required', function($attribute, $value, $fail) use ($number,$util) {

                // Validate length of remaining number
                if (! $util->isValidNumber($number)) {
                    $fail("Invalid phone number");
                }
            }]
        );

        $validator = FacadesValidator::make($request->all(), $rules);

        

        // set nice names
        $validator->setAttributeNames(
            [
                'fullname'  => 'Full Name',
                'firstname' => 'Frist Name',
                'lastname'  => 'Last Name',
                'email'     => 'Email Address',
                'telephone' => 'Telephone Number',
                'terms'     => 'required',
            ]
        );

        $json = array();
        $invalidFeilds = [];
        $grecaptchaStatus=true;


        // process the validation
        if ($validator->fails())
        {
            // get the feilds with error
            $invalidFeilds = invalidFeilds($rules, $validator);
            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message)
            {
                $error_txt .= "$message";
            }

            $request->session()->flash('message_danger', $error_txt);
            $json['alert'] = '<div class="alert alert-danger alert-dismissible show" role="alert">'.$error_txt.'</div>';
            $json["flag"] = 0;
        }
        else
        {
            if($this->captcha_enabled == '1')
            {
                /*--------------------------------------------------
                * GOOGLE reCAPTCHA
                --------------------------------------------------*/
                $grecaptcharesponse = $request->input('recaptcha_token');
                $GRresult = googleRecaptchaV3($grecaptcharesponse,$request->ip());
                /*-------------------------------------------------*/
                if($GRresult->success == false)
                {
                    $json["alert"] = get_flash_alert();
                    $json["alert"] = '<div class="alert alert-danger alert-dismissible show" role="alert">CAPTCHA verification failed</div>';
                    $json["invalidFeilds"] = false;
                    $json["flag"] = 3;

                    $request->session()->flash('message_danger', 'CAPTCHA verification failed!');
                    $grecaptchaStatus=false;
                    echo json_encode( $json );
                }
            }

            $type = '';
            // Data Saving logic

            if($grecaptchaStatus){
                // store
                $enquiry = new Enquiry;
                
                if ($ref !== false)
                {
                    // If Developments Enabled....
                    if(settings('new_developments') == '1')
                    {
                        // Interested In Development...
                        if($request->has('development_unit_interested'))
                        {
                            // Property Enquiry....
                            $enquiry->ref = $ref;
                            $enquiry->category = 'Development Enquiry';
                            $type = 'Development Enquiry';
                        }
                    }
                    else
                    {
                        // Property Enquiry....
                        $enquiry->ref = $ref;
                        $enquiry->category = 'Property Enquiry';
                        $type = 'Property Enquiry';

                    }
                }
                else
                {
                    $enquiry->category = 'Contact Us';
                    $type = 'General Enquiry';

                }

                if ($this->form_category){
                    $enquiry->category = $this->form_category;
                    $type = $this->form_category;
                }

                $enquiry->name = $request->input('firstname').' '.$request->input('lastname');
                $enquiry->firstname = $request->input('firstname');
                $enquiry->lastname = $request->input('lastname');
                $enquiry->email = $request->input('email');
                $enquiry->telephone = $request->input('telephone');
                
                if(empty($enquiry->firstname)){
                    $enquiry->name = $request->input('fullname');
                }


                $data = [];
                if ($request->has('position'))
                {
                    $data['position'] = $request->input('position');
                }

                if ($request->has('preferred_telephone'))
                {
                    $data['preferred_telephone'] = $request->input('preferred_telephone');
                }

                if ($request->has('reason'))
                {
                    $data['reason'] = $request->input('reason');
                }

                if ($request->has('url'))
                {
                    $url = $request->input('url');
                    $enquiry->url = $url;
                    $enquiry->ref = $request->input('ref');
                }

                //Can add additional fields here... same format as position field above...

                if (!empty($this->form_category) && (
                    $this->form_category == 'Find My Dream Home (Home)' ||
                    $this->form_category == 'What’s my home worth? (Bottom)' ||
                    $this->form_category == 'Sell my home (Home)' )
                ){
                    $data['location'] = $request->input('location');
                    $data['town'] = $request->input('town');
                    $data['Postcode'] = $request->input('Postcode');
                    $data['County'] = $request->input('County');
                    $data['Houseno'] = $request->input('Houseno');
                    $data['Streetname'] = $request->input('Streetname');
                    $data['property_type'] = $request->input('property_type');
                    $data['liketodo'] = $request->input('liketodo');
                    $data['parking'] = $request->input('parking');
                    $data['grage'] = $request->input('grage');
                    $data['valuation_message'] = $request->input('valuation_message');
                }

                if(count($data)){
                    $enquiry->data = json_encode($data);
                }

                if(settings('new_developments') == '1')
                {
                    // Interested In Development...
                    if($request->has('development_unit_interested'))
                    {
                        if($request->input('development_unit_interested') != '')
                        {
                            // Find The Development Unit Details...
                            $unit = DevelopmentUnit::find($request->input('development_unit_interested'));

                            $enquiry->message = 'Interested in Development - '.$unit->development->development_title. '. Unit - '. $unit->development_unit_name.'';
                        }
                    }

                }

                if($request->input('message')){
                    $enquiry->message = $request->input('message');
                }

                if($request->has('branch_id'))
                {
                    $enquiry->branch_id = $request->input('branch_id');
                }

                // Always assume Action Type is Create...
                $action_type = "create";

                if($request->has('user_id'))
                {
                    $enquiry->user_id = $request->input('user_id');
                    $user_id = $request->input('user_id');
                    $action_type = "update";
                }
                else
                {
                    // See If We Can Match The Email To User...
                    $found_user = User::whereEmail($request->input('email'))->first();
                    if($found_user){
                        $user_id = $found_user->id;
                        $action_type = "update";
                    }else{
                        // See If We Have Any Existing Enquiries from that User...
                        $existing_enquiry = Enquiry::whereEmail($request->input('email'))->first();
                        if($existing_enquiry)
                        {
                            // Send UUID For That Lead Through...
                            $user_id = $existing_enquiry->uuid;
                            $action_type = "update";
                        }
                    }
                }

                $enquiry->save();



                // Mail portion after data save ...... Logic

                // Split Name into 2 for Subject / Message...
                if($request->has('fullname')){
                    $name = split_name($request->input('fullname'));
                }else{
                    $name = [];
                    $name['first_name']  = $request->has('firstname') ? $request->input('firstname') : '';
                    $name['last_name'] = $request->has('lastname') ? $request->input('lastname') : '';
                }

                // If Propertybase Enabled, Send To Propertybase CRM....
                if(settings('propertybase'))
                {
                    // If No Found User & No Existing Enquiry, Send Through the UUID...
                    if(!$found_user && !$existing_enquiry)
                    {
                        // New Lead (Send UUID)....
                        $user_id = $enquiry->uuid;
                    }

                    if($type == 'General Enquiry')
                    {
                        // Send all Enquiry Data To Propertybase...
                        $this->post_contact_form($enquiry, $user_id);
                    }

                    if($type == 'Property Enquiry')
                    {
                        // Get Property To Send Through...
                        $property = Property::find($request->input('property_id'));

                        // If No User Session - Store a new Session ID...
                        if(Auth::guest())
                        {
                            // Push Into Session - The User ID....
                            session(['user_id' => $user_id]);

                            // Send Lead....
                            $this->post_lead($request->input(), $user_id, $action_type);
                        }
                        else
                        {
                            // Inquiry Form....
                            $this->post_inquiry($enquiry, $property, $user_id, $action_type);
                        }
                    }

                }

                
                $rejectlist = [
                    'ericjonesmyemail@gmail.com',
                    'info@professionalseocleanup.com',
                    'mike@monkeydigital.co',
                    'yawiviseya67@gmail.com',
                    'cheeck-tttt@gmail.com',
                    'ebojajuje04@gmail.com',
                    'info@digital-x-press.com',
                    'abdulrazzakk33@gmail.com',
                ];
                
                $emailcheck = strtolower(trim($request->input('email')));
                $msgcontainer = [];
                if (!in_array($emailcheck,$rejectlist)) {
                    if ($request->has('submission_type') && $request->submission_type == 'home-worth')
                    {
                        // Send To Admin & Secondary Email...
                        SendAdminEmail::dispatch($enquiry);
                    }
                    else
                    {
                        // send to inquiries@terezaestates.com only
                        SendAdminEmail::dispatch($enquiry);
                    }
                
                    // Get Telephone Number Site....
                    // $phone_number = settings('telephone'); SendEnquiryEmail::dispatch($enquiry);
                    $msgcontainer['sub'] = 'Thank you for your enquiry '.ucwords($name['first_name']).ucwords($name['last_name']).'!';
                    $message = '<p>Hello '. $name['first_name'].', thank you for your '. $type .'.</p>';
                    $message .= '<p>We will be in touch shortly regarding your query, however if you want to get in contact with us directly, you can call us on <strong style="color:#d9b483"> +971 585 365 111 </strong> where we will be happy to chat with you.</p>';
                    $message .= '<p>Once again thank you for enquiring with us and we are looking forward to speaking with you soon.</p>';

                    $msgcontainer['msg'] = $message;
                    //User email
                    SendEnquiryEmail::dispatch($enquiry,$msgcontainer);
                    
                    if(!Subscriber::where('email',$request->input('email'))->exists()){
                        if ($request->has('newsletter'))
                        {
                            $subscribers = new Subscriber;
                            $subscribers->fullname = $request->input('firstname').' '.$request->input('lastname');
                            $subscribers->email = $request->input('email');
                            $subscribers->telephone = $request->input('telephone');
                            $subscribers->save();
                    
                            // Config::get('mail.from.address')
                            //Admin email
                            SendSubscribeEmail::dispatch('faizdev007@gmail.com',$subscribers);

                            $msgcontainer['sub'] = 'Thank You!';
                            $msgcontainer['msg'] = 'Thank you for subscribing to our newsletters. We will keep you '.$request->input('email').' up to date with the latest news from <br> <strong style="color:#d9b483"> '.settings('site_name').'! </strong>';
                            SendEnquiryEmail::dispatch($enquiry,$msgcontainer);
                        }
                    }
                }
                else {
                    if(!Subscriber::where('email',$request->input('email'))->exists()){
                        // Still save newsletter subscription if requested, but without sending emails
                        if ($request->has('newsletter')) {
                            $subscribers = new Subscriber;
                            $subscribers->fullname = $request->input('firstname').' '.$request->input('lastname');
                            $subscribers->email = $request->input('email');
                            $subscribers->telephone = $request->input('telephone');
                            $subscribers->save();
                        }
                    }
                }
                $request->session()->flash('message_success', 'Thank You - Your details have been received!');
                $json['alert'] = '<div class="alert alert-success alert-dismissible show" role="alert">Thank You - Your details have been received!</div>';
                $json["flag"] = 1;
            }

        }

        $json["alert"] = get_flash_alert();
        $json["invalidFeilds"] = !empty($invalidFeilds) ? json_encode($invalidFeilds) : false;
        if($grecaptchaStatus){
            echo json_encode( $json );
        }

    }

    // Send To Mailchimp List...
    /* \Mailchimp::subscribe(
        config('custom.mailchimp.newsletter_list'), $request->input('email'),
        $merge = [
            'FNAME' => $request->firstname,
            'LNAME' => $request->lastname
        ],
        $confirm = true
    );*/
    // Use $confirm = false to skip double-opt-in if you already have permission.

    // redirect

    public function request_viewing(Request $request)
    {
        $enquiry = new Enquiry;
        $enquiry->ref = $request->input('property_ref');
        $enquiry->category = 'Viewing Request';
        $enquiry->name = $request->input('name');
        $enquiry->email = $request->input('email');
        $enquiry->telephone = $request->input('phone');

        // Now Build Message...
        $message = "Hi, I would like to view this property.";
        $message.= "<br /><br />";
        $message.= "My chosen date is ". $request->input('date');
        $message.= "<br /><br />";

        $times = explode(',', $request->input('times'));

        $message.= "I would like to view this property at any of these times : ";
        $message.= "<br />";
        foreach($times as $time)
        {
            $message.= $time .',';
            $message.= "<br /><br />";
        }

        $enquiry->message = $message;
        $enquiry->url = $request->input('url');
        $enquiry->save();

        // Config::get('mail.from.address')
        // send email to admin
        SendEnquiryEmail::dispatch($enquiry);

        return response()->json(
            [
                'message' => 'Thanks for your booking request, an agent will be in touch shortly'
            ]
        );

    }

    /*
    * Generic page sidebar enquiry form
    */
    public function generic_enquiry(Request $request)
    {
        $this->form_category = 'Generic Sidebar';
        $this->enquiry($request, false);
    }

    /*
    * Property sidebar enquiry form
    */
    public function property_sidebar_form(Request $request)
    {
        $this->form_category = 'Property Enquiry (Sidebar)';
        $this->enquiry($request, false);
    }

    /*
    * Property sidebar enquiry form
    */
    public function property_bottom_form(Request $request)
    {
        $this->form_category = 'Property Enquiry (Bottom)';
        $this->enquiry($request, false);
    }

    /*
    * Property slider enquiry form
    */
    public function property_slider_form(Request $request)
    {
        $this->form_category = 'Property Enquiry (Slider)';
        $this->enquiry($request, false);
    }

    /*
    * Valuation enquiry form
    */
    public function valuation(Request $request)
    {
        $this->form_category = 'Valuation';
        $this->enquiry($request, false);
    }

    public function bottom_generic(Request $request)
    {
        $this->form_category = 'Generic Form (Bottom)';
        $this->enquiry($request, false);
    }

    public function home_cta_1(Request $request)
    {
        $this->form_category = 'Find My Dream Home (Home)';
        $this->enquiry($request, false);
    }

    public function home_cta_2(Request $request)
    {
        $this->form_category = 'What’s my home worth? (Bottom)';
        $this->enquiry($request, false);
    }

    public function home_cta_3(Request $request)
    {
        $this->form_category = 'Sell my home (Home)';
        $this->enquiry($request, false);
    }

}
