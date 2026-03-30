<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UserResetPasswordController extends Controller
{
    protected $redirectTo = '/account';

    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function guard()
    {
        return Auth::guard('member');
    }

    public function reset(Request $request)
    {
        // ✅ Validate request
        $request->validate([
            'token'             => 'required',
            'email'             => 'required|email|exists:users,email',
            'password'          => 'required|string|min:6|confirmed',
            'recaptcha_token'   => 'required',
        ]);

        // ✅ reCAPTCHA check
        if (!googleRecaptchaV3($request->recaptcha_token, $request->ip())['success']) {
            return back()
                ->withInput($request->only('email'))
                ->with('message_warning', 'Invalid reCAPTCHA verification.');
        }

        // ✅ Fetch reset token
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return back()->with('message_warning', 'Invalid or expired reset token.');
        }

        if (!hash_equals($tokenData->token, $request->token)) {
            return back()->with('message_warning', 'Invalid reset token.');
        }

        // ✅ Fetch user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('message_warning', 'Account not found.');
        }

        // ✅ Optional spam rule
        if (!Str::startsWith($user->telephone ?? '', '+')) {
            return back()->with('message_warning', 'Invalid spam user.');
        }

        // ✅ Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // ✅ Delete token
        DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->delete();

        // ✅ Auto-login
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/account')
            ->with('message_success', 'Your password has been reset successfully.');
    }

    public function showResetForm(Request $request, $token)
    {
        $view = view()->exists('frontend.' . themeOptions() . '.auth.passwords.reset')
            ? 'frontend.' . themeOptions() . '.auth.passwords.reset'
            : 'frontend.shared.auth.passwords.reset';

        return view($view, [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function checkEmailExists(Request $request)
    {
        return response()->json([
            'exists' => User::where('email', $request->email)->exists(),
        ]);
    }

    protected function broker()
    {
        return Password::broker('members');
    }
}