<?php
namespace App\Http\Controllers\Frontend;

use Config;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\PropertyAlertMail;
use App\Mail\PropertyAlertCronMail;
use App\Mail\GenericMail;
use App\Property;
use App\PropertyAlert;
use App\PropertyType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendEnquiryEmail;
use App\Jobs\SendPropertyAlertEmail;
use Illuminate\Support\Str;

class PropertyAlertController extends Controller
{
    private $captcha_enabled;

    public function __construct()
    {
        // See If Captcha is Enabled...
        $this->captcha_enabled = settings('recaptcha_enabled');

        // See If Members Area is Enabled....
        $this->members = settings('members_area');

        $this->middleware(function ($request, $next)
        {
            if(Auth::check())
            {
                $this->user_id = Auth::user()->id;
            }

            return $next($request);

        });
    }


    public function cron()
    {
        $TEST = FALSE;

        $alerts = PropertyAlert::where([['is_active',1]])->get();

        $oneEmailPerDay = [];

        if($TEST){
            echo 'Properties created:<br/>';
            echo $date_24hrs_before = date('Y-m-d H:i:s',strtotime('-24 hours'));
            echo '<br/>';
            echo $date_now = date('Y-m-d H:i:s');
        }

        foreach($alerts as $alert){

            $criteria['for'] = ($alert->is_rental==1) ? 'rent' : 'sale';
            $criteria['in'] = $alert->in;

            if(!empty($alert->property_type_ids)){
                $criteria['property-type-ids'] = explode(',', $alert->property_type_ids);
            }

            $criteria['price_range'] = $alert->price_range;
            $criteria['beds'] = $alert->beds;
            $criteria['oneDay'] = 1; //Last 24hours added

            if($TEST){
                echo '<hr/>';
                print_R($criteria);
                echo '<br/><br/>';
            }

            $property = new Property();

            $properties = $property->searchWhere($criteria, FALSE, 8);

            if(!$TEST){
                if( !in_array($alert->email, $oneEmailPerDay) ){
                    if( $properties->count() ){
                        echo "<hr/>";
                        echo $alert->email;
                        echo "<br/>";
                        echo "# of Properties: ".count($properties);
                        SendPropertyAlertEmail::dispatch($properties, $alert);
                        $oneEmailPerDay[] = $alert->email; //one email per day...
                    }
                }
            }

            if($TEST){
              echo "<hr/>";
              echo $alert->email;
              echo "<br/>";
              echo "# of Properties: ".count($properties);
              echo "<br/>";
            }

        }

    }

    public function unsubscribe(Request $request)
    {
        $id = $request->input('id');
        $PropertyAlert = PropertyAlert::find($id);

        if(!empty($PropertyAlert)){
            $PropertyAlert->is_active = 0;
            $PropertyAlert->save();
        }

        $data = [ 'success' => 'Successfully Unsubscribe!' ];
        return redirect(url('unsubscribe'))->with($data);
    }

    public function add(Request $request)
    {
        $rules = [
            'fullname'=>'required|max:80',
            'email'=>'required|email|max:80',
            'contact'=>'max:80',
        ];

        $validator = Validator::make($request->all(), $rules);

        $validator->setAttributeNames([
            'email'     => 'Email Address',
        ]);

        if ($validator->fails())
        {
            $invalidFeilds = invalidFeilds($rules, $validator);
            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message)
            {
                $error_txt .= "$message ";
            }

            $json["flag"] = 0;
        }
        else
        {
            if($request->has('members_area')) { }else{
                if($this->captcha_enabled == '1')
                {
                    /*--------------------------------------------------
                    * GOOGLE reCAPTCHA
                    --------------------------------------------------*/
                    $grecaptcharesponse = $request->input('recaptcha_token');
                    $GRresult = googleRecaptchaV3($grecaptcharesponse,$request->ip());
                    /*-------------------------------------------------*/

                    if(!$GRresult->success)
                    {
                        $json["alert"] = '<div class="alert alert-danger alert-dismissible show" role="alert">CAPTCHA verification failed</div>';
                        $json["invalidFeilds"] = false;
                        $json["flag"] = 3;

                        $request->session()->flash('message_danger', 'CAPTCHA verification failed!');

                        return response()->json($json);
                    }
                }
            }

            $PropertyAlert = new PropertyAlert;

            $price_range = $request->input('price_range');

           

            if(!empty($price_range))
            {
                list($price_from, $price_to) = explode('-', $price_range);
                $PropertyAlert->price_from = $price_from;
                $PropertyAlert->price_to = $price_to;
            }

            // $PropertyAlert->price_from = $request->input('min_price');
            // $PropertyAlert->price_to = $request->input('max_price');

            $PropertyAlert->fullname = $request->input('fullname');
            $PropertyAlert->email = $request->input('email');
            $PropertyAlert->contact = $request->input('contact');

            $PropertyAlert->is_rental = ($request->input('is_rental') == 1) ? 1 : 0;
            $PropertyAlert->in = $request->input('in');

            if ($request->has('property_type_id')){
                $PropertyAlert->property_type_ids = implode(',',$request->input('property_type_id'));
            }

            $PropertyAlert->beds = $request->input('beds');
            $PropertyAlert->is_active =  1;

            // If Members Area is Enabled & User is Logged In....
            if($this->members == '1' && Auth::check())
            {
                $PropertyAlert->user_id = $this->user_id;
            }

            
            
            $PropertyAlert->save();
            
            //$PropertyAlert->token =  Str::random(32);
            //For email display
            $PropertyAlert->url_from = $request->input('url_from');
            $PropertyAlert->for = ($PropertyAlert->is_rental==1) ? 'Rent' : 'Sale';

            $propertyType="";

            if(!empty($PropertyAlert->property_type_id))
            {
                $propertyType = PropertyType::find($PropertyAlert->property_type_id);
                $propertyType=$propertyType->name;
            }

            $PropertyAlert->property_type = $propertyType;

            if($this->members == '1' && Auth::check())
            {
                $request->session()->flash('message_success', 'Your alert has been saved!');
            }
            else
            {
                // Only send out Emails if Non Members are saving alerts...

                //Admin email
                SendPropertyAlertEmail::dispatch($PropertyAlert);

                //User email
                $message = [];
                $message['sub'] = 'Property Alert | '.settings('site_name');
                $message['msg'] = '<p>Thank you for using Property Alert. We will be in touch shortly.</p><p>If you require urgent assistance, please contact us.</p>'.settings('site_name').'!';
                SendEnquiryEmail::dispatch(null,$message,$request->input('email'));
                $request->session()->flash('message_success', 'Your details have been sent!');
            }

            $json['flag'] = 1;

        }

        if($request->has('members_area'))
        {
            $request->session()->flash('message_success', 'Your property alert has been saved!');
            return redirect()->back();
        }
        else
        {
            $json["alert"] = get_flash_alert();
            $json["invalidFeilds"] = !empty($invalidFeilds) ? json_encode($invalidFeilds) : false;
            echo json_encode( $json );
        }


    }

    public function update($id, Request $request)
    {
        // Update The Record...
        $alert = PropertyAlert::find($id);
        $alert->is_rental = $request->input('is_rental') ?? $alert->is_rental;
        $alert->in = $request->input('in') ?? $alert->in;
        //$alert->property_type_id = $request->input('property_type_id');
        
        if ($request->has('property_type_id')){
            $alert->property_type_ids = implode(',',$request->input('property_type_id'));
        }else{
            $alert->property_type_ids = '';
        }


        $alert->beds = $request->input('beds');

        $price_range = $request->input('price_range');

        if(!empty($price_range))
        {
            list($price_from, $price_to) = explode('-', $price_range);
            $alert->price_from = $price_from;
            $alert->price_to = $price_to;
        }

        // $alert->price_from = $request->input('min_price');
        // $alert->price_to = $request->input('max_price');

        $alert->save();

        // Confirm Save...
        $request->session()->flash('message_success', 'Your alert has been updated!');

        return redirect()->back();

    }

    public function destroy($id)
    {
        $propertyalert = PropertyAlert::find($id);

        if($propertyalert)
        {
            PropertyAlert::destroy($id);

            session()->flash('message_success', 'Alert deleted successfully!');

            return redirect()->back();

            // // Send Confirmed Response...
            // return response()->json(
            //     [
            //         'id'   => $id,
            //         'error' => 'false',
            //         'remaining' => PropertyAlert::where('user_id', Auth::user()->id)->count(),
            //         'message' => 'Property Alert Deleted!'
            //     ]
            // );
        }
    }


}
