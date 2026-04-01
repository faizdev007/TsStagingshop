<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Archive;
use App\Http\Controllers\Controller;
use App\Page;
use App\Shortlist;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

class UserLoginController extends Controller
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

    use Authenticatable;

    protected $guard = 'user';

    public function __construct()
    {
        $this->middleware( 'guest' )->except( 'logout' );
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        // If Bespoke View Exists...
        $view = 'frontend.demo1.auth.login-register';

        $view_data = array(
            'page' => Page::where('route', 'login')->first()
        );

        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view,  $view_data);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.auth.login-register', $view_data);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->flash('message_success', 'Successfully logged out.');

        return redirect('/login');
    }

    public function userLogin(Request $request)
    {
        // 1️⃣ Validate input
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()
                ->with('warning', $validator->errors()->first())
                ->withInput();
        }

        // 2️⃣ Fetch user safely
        $user = User::where('email', $request->email)->first();

        // 6️⃣ Admin redirect (role_id !== 4)
        if ($user->role_id !== 4) {
            return redirect('/admin');
        }

        if (! $user) {
            return back()
                ->with('warning', 'Invalid email or password.')
                ->withInput();
        }

        // 3️⃣ Block suspended users
        if ($user->status != 1) {
            return back()
                ->with('warning', 'Your account is suspended.')
                ->withInput();
        }

        // 4️⃣ Attempt login ONCE
        if (! Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            return back()
                ->with('warning', 'Invalid email or password.')
                ->withInput();
        }

        // 5️⃣ Regenerate session (security)
        $request->session()->regenerate();

        // 7️⃣ Optional: Add property to favourites
        if ($request->filled('property_id')) {
            (new Shortlist())->add($request->property_id, Auth::id());
        }

        // 8️⃣ Success message
        return redirect()
            ->intended('account')
            ->with('success', 'Login successful! Welcome back.');
    }

    public function customLogin(Request $request)
    {
        // 1️⃣ Validate input
        $validator = Validator::make($request->all(), [
            'email'    => ['required', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()
                ->with('warning', $validator->errors()->first())
                ->withInput();
        }

        $finduser = User::where('email', $request->email)->first();
        
        if(!$finduser){
            return response()->json([
                'flag'     => 3,
                'message'  => 'User not found.',
            ]);
        }

        // 6️⃣ Admin redirect (role_id !== 4)
        if ($finduser->role_id !== 4) {
            return response()->json([
                'flag'     => 1,
                'message'  => 'Please use the admin login.',
                'redirect' => url('/admin'),
            ]);
        }


        // 2️⃣ Fetch user safely
        $user = Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ]);

        if (! $user) {
            return back()
                ->with('warning', 'Invalid email or password.')
                ->withInput();
        }

        $currentuser = Auth::user();

        // 3️⃣ Block suspended users
        if ($currentuser->status != 1) {
            return back()
                ->with('warning', 'Your account is suspended.')
                ->withInput();
        }

        // 5️⃣ Regenerate session (security)
        $request->session()->regenerate();

        // 7️⃣ Optional: Add property to favourites
        if ($request->filled('property_id')) {
            (new Shortlist())->add($request->property_id, Auth::id());
        }

        // 7. Success response
        return response()->json([
            'flag'     => 1,
            'message'  => 'Login successful! Welcome back.',
            'redirect' => url('account'),
        ]);
    }
}
