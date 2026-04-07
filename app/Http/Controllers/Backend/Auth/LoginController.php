<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendOTPEmail;
use App\Jobs\SendEnquiryEmail;
use App\Shortlist;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthorizesRequests;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'verifyOTP']);
    }

    public function sendOTP(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // 2️⃣ Fetch user safely
        $exists = User::where('email', $request->email)
              ->where('role_id', '!=', 4)
              ->exists();

        // 6️⃣ Admin redirect (role_id == 4)
        if (!$exists) {
            return redirect('/login');
        }
        
        if (Auth::validate($credentials)) {
            $user = Auth::getProvider()->retrieveByCredentials($credentials);
            
            // Generate OTP
            $otp = Str::padLeft(random_int(0, 999999), 6, '0');
            
            // Store OTP in cache for 5 minutes
            Cache::put('login_otp_' . $user->id, $otp, now()->addMinutes(5));
            
            // Send OTP email
            
            SendOTPEmail::dispatch($otp,$user);
            
            // Store user credentials in session temporarily
            session(['temp_login_user_id' => $user->id]);
            
            return response()->json(['success' => true]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $userId = session('temp_login_user_id');
        $inputOTP = $request->input('otp');
        
        if (!$userId || !$inputOTP) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request'
            ]);
        }
        
        $storedOTP = Cache::get('login_otp_' . $userId);
        
        if ($storedOTP && $storedOTP === $inputOTP) {
            $user = \App\User::find($userId);
            
            $lastLoginAt = Carbon::now()->toDateTimeString();
            $Logindata = Carbon::now()->toDateString(); 

            $message['sub'] = "User {$user->name} ({$user->email}) successfully logged in to the admin dashboard at {$Logindata}";
            $message['msg'] = "User {$user->name} ({$user->email}) Login to Dashboard at {$lastLoginAt}!";
            
            if ($user !== 4) {
                Auth::login($user);
                
                // Clear OTP and temporary session data
                Cache::forget('login_otp_' . $userId);
                session()->forget('temp_login_user_id');
                
                // Update last login details
                $user->update([
                    'last_login_at' => Carbon::now()->toDateTimeString(),
                    'last_login_ip' => $request->getClientIp()
                ]);
                
                SendEnquiryEmail::dispatch(null,$message,Config::get('mail.from.address'));
                
                return response()->json(['success' => true]);
            }else{
                return redirect('/login',302);
            }
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid OTP'
        ]);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
      return view('backend.auth.login');
    }

    public function logout(Request $request) {
      Auth::logout();
      return redirect('/admin');
    }

    function authenticated(Request $request, $user)
    {

        //Check if user not suspended
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 0]))
        {
            Auth::logout();
            $request->session()->flash('message_danger', 'Suspended account.');
            return redirect('/admin/login');
        }

        $user->update(
            [
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]
        );

        if($request->session()->has('redirect.url'))
        {
            if($request->session()->has('property_id'))
            {
                // Save Property ID in Shortlist...
                $property_id = $request->session()->get('property_id');

                $shortlist = new Shortlist();
                $shortlist->add($property_id, $user->id);
            }

            return redirect( session()->get( 'redirect.url' ) );
        }
    }

}
