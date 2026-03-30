<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Enquiry;
use App\Page;
use App\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\PropertyBaseTrait;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class UserRegisterController extends Controller
{
    use PropertyBaseTrait;

    protected $redirectTo = '/account';

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function showRegistrationForm()
    {
        $view_data = array(
            'page' => Page::where('route', 'login')->first()
        );

        // Bespoke Login / Register
        if (view()->exists('frontend.demo1.auth.login-register'))
        {
            return view('frontend.demo1.auth.login-register', $view_data);
        }
        else
        {
            return view('frontend.shared.auth.login-register', $view_data);
        }
    }

    public function register(Request $request)
    {
        // validate
        $validator = Validator::make($request->all(), [
            'first_name'        => 'required|max:255',
            'surname'           => 'required|max:255',
            'email'             => 'required|email|unique:users,email',
            'telephone'         => 'required|regex:/^\+[0-9]/',
            'password'          => [
                'required',
                'string',
                'min:6',
                'confirmed',
                'regex:/^[A-Z]/' // Requires first letter to be capital
            ]
        ], [
            'telephone.regex' => 'Invalid phone number format. Please Select the country code',
            'password.regex' => 'Password must start with a capital letter'
        ]);

        if ($validator->fails())
        {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Spam detection
        $spamCheck = $this->sophisticatedSpamDetection($request);

        if ($spamCheck['is_spam']) {
            return back()
            ->withErrors(['spam' => 'Registration blocked due to suspicious activity.'])
            ->withInput();
        }

        // Validation Passed, create user
        $name = $request->input('first_name') . ' ' . $request->input('surname');
        $user = new User;
        $user->name = $name;
        $user->firstname = $request->input('first_name');
        $user->lastname = $request->input('surname');
        $user->email = $request->input('email');
        $user->telephone = $request->input('telephone');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = 4; // Always User for Frontend Reg...
        $user->status = '1'; // 1 For Now?
        $user->save();

        // See If User Has Made Any Enquiries and assign user ID to their leads...
        $existing_leads = Enquiry::where('email', $request->input('email'))->get();



        if($existing_leads->count() > 0)
        {
            foreach($existing_leads as $lead_update)
            {
                $update_lead = Enquiry::find($lead_update->id);
                $update_lead->user_id = $user->id;
                $update_lead->save();
            }
        }

        if(settings('propertybase'))
        {
            // Send Data To Propertybase (New User)...
            $this->post_lead($request->input());
        }

        // Log this user in....
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials))
        {
            // Set Last Login & Login IP...
            $user->update(
                [
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->getClientIp()
                ]
            );

            // Confirm Registration (Show Flash)...
            $request->session()->flash('message_success', 'Registration Successful!');

            // Authentication passed...
            return redirect()->intended('account');
        }
    }

    public function customRegister(Request $request)
    {
        // 1. Validate request (AJAX-safe)
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'max:255'],
            'surname'    => ['required', 'max:255'],
            'email'      => ['required', 'email', 'unique:users,email'],
            'telephone'  => ['required', 'regex:/^\+[0-9]/'],
            'password'   => [
                'required',
                'confirmed',
                'min:8',
                'max:190',
                'regex:/^[A-Z]/',
            ],
        ], [
            'telephone.regex' => 'Invalid phone number format. Please select the country code.',
            'password.regex'  => 'Password must start with a capital letter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'flag'    => 0,
                'message' => $validator->errors()->first(),
            ]);
        }

        // 2. Spam detection
        $spamCheck = $this->sophisticatedSpamDetection($request);

        if ($spamCheck['is_spam']) {
            return response()->json([
                'flag'    => 0,
                'message' => 'Registration blocked due to suspicious activity.',
            ]);
        }

         // Validation Passed, create user
        $name = $request->input('first_name') . ' ' . $request->input('surname');
        $user = new User;
        $user->name = $name;
        $user->firstname = $request->input('first_name');
        $user->lastname = $request->input('surname');
        $user->email = $request->input('email');
        $user->telephone = $request->input('telephone');
        $user->password = Hash::make($request->input('password'));
        $user->role_id = 4; // Always User for Frontend Reg...
        $user->status = '1'; // 1 For Now?
        $user->save();

        // 4. Attach existing enquiries
        Enquiry::where('email', $user->email)
            ->update(['user_id' => $user->id]);

        // 5. Propertybase integration
        if (settings('propertybase')) {
            $this->post_lead($request->all(), $user->id);
        }

        // 6. Auto login
        Auth::login($user);
        $request->session()->regenerate();

        // Update login metadata
        $user->update([
            'last_login_at' => Carbon::now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Flash success message
        session()->flash(
            'message_success',
            'Thank you for registering with ' . settings('site_name') .
            '. You may shortlist properties, save searches and set property alerts.'
        );

        // 7. Success response
        return response()->json([
            'flag'     => 1,
            'message'  => 'Registration successful.',
            'redirect' => url('account'),
        ]);
    }

    public function check_email_exists(Request $request)
    {
        $email_check = User::where('email', $request->email)->get();

        if($email_check->count() > 0)
        {
            return response('Email Address already exists', 400);
        }
        else
        {
            return response('', 200);
        }
    }

    protected function sophisticatedSpamDetection(Request $request): array
    {
        $spamScore   = 0;
        $spamReasons = [];

        /*
        |--------------------------------------------------------------------------
        | 1. Email Domain Reputation
        |--------------------------------------------------------------------------
        */
        if (!empty($request->email) && Str::contains($request->email, '@')) {
            $emailDomain = strtolower(substr(strrchr($request->email, "@"), 1));
            $suspiciousDomains = [
                'temp-mail.org',
                'guerrillamail.com',
                'throwawaymail.com',
                'mailinator.com',
                'yopmail.com',
            ];

            if (in_array($emailDomain, $suspiciousDomains, true)) {
                $spamScore += 3;
                $spamReasons[] = 'Suspicious email domain';
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2. Advanced Bot Detection (Safe user agent handling)
        |--------------------------------------------------------------------------
        */
        $userAgent = strtolower($request->userAgent() ?? '');

        if ($userAgent !== '') {
            $botPatterns = [
                'bot', 'spider', 'crawler', 'curl', 'wget',
                'python-requests', 'python', 'http_request',
                'masscan', 'nikto', 'sqlmap',
            ];

            foreach ($botPatterns as $pattern) {
                if (Str::contains($userAgent, $pattern)) {
                    $spamScore += 3;
                    $spamReasons[] = 'Potential bot user agent';
                    break;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 3. IP-based Flood Protection (Atomic & safe)
        |--------------------------------------------------------------------------
        */
        $ipKey = 'registrations:' . $request->ip();

        $recentRegistrations = Cache::increment($ipKey);
        Cache::put($ipKey, $recentRegistrations, now()->addHours(24));

        if ($recentRegistrations > 5) {
            $spamScore += 4;
            $spamReasons[] = 'Excessive registrations from IP';
        }

        /*
        |--------------------------------------------------------------------------
        | 4. Name Validation (Avoid undefined index errors)
        |--------------------------------------------------------------------------
        */
        if (
            empty($request->first_name) || strlen(trim($request->first_name)) < 2 ||
            empty($request->surname) || strlen(trim($request->surname)) < 2
        ) {
            $spamScore += 2;
            $spamReasons[] = 'Suspicious name length';
        }

        /*
        |--------------------------------------------------------------------------
        | 5. Email Pattern Check
        |--------------------------------------------------------------------------
        */
        if (!empty($request->email) && preg_match('/\d{5,}/', $request->email)) {
            $spamScore += 2;
            $spamReasons[] = 'Suspicious email pattern';
        }

        /*
        |--------------------------------------------------------------------------
        | 6. Geolocation / Proxy Detection (Timeout-safe)
        |--------------------------------------------------------------------------
        */
        try {
            $response = Http::timeout(2)->get(
                "http://ip-api.com/json/{$request->ip()}",
                ['fields' => 'status,countryCode,proxy,hosting']
            );

            if ($response->ok()) {
                $location = $response->json();

                if (
                    ($location['proxy'] ?? false) === true ||
                    ($location['hosting'] ?? false) === true
                ) {
                    $spamScore += 2;
                    $spamReasons[] = 'VPN or proxy detected';
                }

                $suspiciousCountries = ['RU', 'CN', 'IR'];
                if (in_array($location['countryCode'] ?? '', $suspiciousCountries, true)) {
                    $spamScore += 2;
                    $spamReasons[] = 'Suspicious geolocation';
                }
            }
        } catch (\Throwable $e) {
            // Do NOT block registration on external API failure
        }

        /*
        |--------------------------------------------------------------------------
        | Final Decision
        |--------------------------------------------------------------------------
        */
        return [
            'is_spam' => $spamScore >= 5,
            'score'   => $spamScore,
            'reasons' => $spamReasons,
        ];
    }
}