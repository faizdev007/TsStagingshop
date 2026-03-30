<?php
namespace App\Http\Controllers\Frontend;

use Config;
use Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscribeMail;
use App\Mail\GenericMail;
use App\Subscriber;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendEnquiryEmail;
use App\Jobs\SendSubscribeEmail;
use Illuminate\Support\Facades\Config as FacadesConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubscribersController extends Controller
{

    private $captcha_enabled;

    /**
     * Instantiate a new UserController instance.
     * Assuming only Ajax function use this cobntroller
     */
    public function __construct()
    {
        $this->captcha_enabled = DB::table('settings')
            ->where('name', 'recaptcha_enabled')
            ->pluck('val')
            ->first();
    }

    public function subscribe(Request $request)
    {
        $rules = [
            'fullname' => 'required|string',
            'email' => 'required|email|max:80',
            'telephone' =>  'required|string|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);

        $validator->setAttributeNames([
            'email' => 'Email Address',
        ]);

        if ($validator->fails()) {
            $invalidFields = invalidFeilds($rules, $validator);
            $errors = $validator->errors()->all();
            $errorTxt = implode(' ', $errors);

            $json = [
                'flag' => 0,
                'alert' => '<div class="alert alert-danger alert-dismissible show" role="alert">' . $errorTxt . '</div>',
                'invalidFeilds' => !empty($invalidFields) ? json_encode($invalidFields) : false,
            ];

            return response()->json($json);
        }

        $email = $request->input('email');
        $email_confirm = $request->input('email_confirm');

        // reCAPTCHA check (if enabled)
        if ($this->captcha_enabled == '1') {

            $grecaptcharesponse = $request->input('recaptcha_token');
            $GRresult = googleRecaptchaV3($grecaptcharesponse,$request->ip());
            if (!$GRresult->success) {
                $json = [
                    'flag' => 3,
                    'alert' => '<div class="alert alert-danger alert-dismissible show" role="alert">CAPTCHA verification failed!</div>',
                    'invalidFeilds' => false,
                ];
                $request->session()->flash('message_danger', 'CAPTCHA verification failed!');
                return response()->json($json);
            }

        }

        // Check if already subscribed
        if (Subscriber::where('email', $email)->exists()) {
            $json = [
                'flag' => 2,
                'alert' => '<div class="alert alert-warning alert-dismissible show text-center" role="alert">Your email address is already registered for our newsletter!</div>',
                'invalidFeilds' => false,
            ];
            $request->session()->flash('message_warning', 'Your email address is already registered for our newsletter!');
            return response()->json($json);
        }

        // Proceed if not already subscribed and honeypot is empty
        if (empty($email_confirm)) {

            $subscriber = new Subscriber;
            $subscriber->fullname = ucwords($request->input('fullname'));
            $subscriber->email = $email;
            $subscriber->telephone = $request->input('telephone');
            $subscriber->save();

            
            $request->session()->flash('message_success', 'Your details have been sent!');

            $json = [
                'flag' => 1,
                'alert' => '<div class="alert alert-success alert-dismissible show" role="alert">Subscription successful!</div>',
                'invalidFeilds' => false,
            ];

            // ✅ Send JSON response immediately
            response()->json($json)->send();

            // ✅ Let PHP finish HTTP response before background mail
            if (function_exists('fastcgi_finish_request')) {
                fastcgi_finish_request();
            }

            // ✅ Send emails in background (no queue, no cron)
            try {

                // Send notification to admin FacadesConfig::get('mail.from.address')
                SendSubscribeEmail::dispatch('faizdev007@gmail.com',$subscriber);
                
                $message = [];
                $message['sub'] = 'Thank You!';
                $message['msg'] = 'Thank you for subscribing to our newsletter! We will keep you (' . $email . ') updated with the latest news from ' . settings('site_name') . '!';
                
                // Send confirmation to user
                SendEnquiryEmail::dispatch(null,$message,$email);

            } catch (\Exception $e) {
                Log::error('Background mail sending failed: ' . $e->getMessage());
            }

            return; // stop here (we already sent response above)
        }

        // Honeypot triggered (bot)
        $json = [
            'flag' => 1,
            'alert' => '<div class="alert alert-success alert-dismissible show" role="alert">Your details have been sent!</div>',
            'invalidFeilds' => false,
        ];

        $request->session()->flash('message_success', 'Your details have been sent!');
        return response()->json($json);
    }
}
