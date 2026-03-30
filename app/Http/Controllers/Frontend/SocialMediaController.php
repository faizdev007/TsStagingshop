<?php

namespace App\Http\Controllers\Frontend;

use App\Enquiry;
use App\Models\SocialMedia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Traits\SocialMediaTrait;
use Illuminate\Support\Str;

class SocialMediaController extends Controller
{
    private $app_id;
    private $app_secret;

    use SocialMediaTrait;

    public function __construct()
    {
        // Get Config...
        $this->app_id = config('facebook.config.fb_app_id');
        $this->app_secret = config('facebook.config.app_secret');
    }


    public function facebook_webhook(Request $request)
    {
        // Verify Webhook....
        if ($request->input('hub_verify_token') === 'PW5565')
        {
            echo $_REQUEST['hub_challenge'];
            exit;
        }

        // Collect Data...
        if(!empty($data))
        {
            Log::info(print_r($data, true));

            $leadgen_ids = array();
            $page_ids = array();
            $form_ids = array();

            foreach ($data->entry as $entry)
            {
                foreach ($entry->changes as $change)
                {
                    $leadgen_ids[] = $change->value->leadgen_id;
                    $page_ids[] = $change->value->page_id;
                    $form_ids[] = $change->value->form_id;
                }
            }
        }

        // Get Social Media Access Token....
        $access_token = SocialMedia::select('token')->where('social_media_type','facebook')->first();

        //$leadgen_ids = array('478887299574085');

        if(!empty($leadgen_ids))
        {
            Log::info('Created'. count($leadgen_ids).' leads from Facebook');

            foreach($leadgen_ids as $leadgen_id)
            {
                $lead_data = $this->get_lead($leadgen_id, $access_token->token);

                $message = '';
                $name = '';

                if($lead_data)
                {
                    foreach($lead_data['field_data'] as $field_data)
                    {
                        if($field_data['name'] == 'first_name')
                        {
                            $name.= $field_data['values'][0] ;
                        }

                        if($field_data['name'] == 'last_name')
                        {
                            $name.= $field_data['values'][0];
                        }

                        if($field_data['name'] == 'email')
                        {
                            $email = $field_data['values'][0];
                        }

                        if($field_data['name'] == 'phone_number')
                        {
                            $phone = $field_data['values'][0];
                        }

                        if(!empty($field_data['values'][0]))
                        {
                            $message .= ucfirst(str_replace('_', ' ', $field_data['name'])) .' : ';
                            $message .= $field_data['values'][0] . ' <br />';
                        }
                    }

                    // Create Enquiry Record...
                    $lead = new Enquiry;
                    $lead->ref = $lead_data['id'];
                    $lead->category = "Facebook Lead";
                    $lead->name = $name;
                    $lead->email = $email;
                    $lead->telephone = $phone;
                    $lead->message = $message;
                    $lead->created_at = Carbon::parse($lead_data['created_time']);
                    $lead->save();
                }
            }
        }

    }
}
