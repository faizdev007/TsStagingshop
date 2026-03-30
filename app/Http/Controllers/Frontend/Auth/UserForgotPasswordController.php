<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

class UserForgotPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        if (view()->exists('frontend.' . themeOptions() . '.auth.passwords.email')) {
            return view('frontend.' . themeOptions() . '.auth.passwords.email');
        }

        return view('frontend.shared.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // ✅ Validate email
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // ✅ Check user exists
        $user = DB::table('users')->where('email', $request->email)->first();

        if (!$user) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => "We can't find a user with that email address."]);
        }

        // ✅ Send reset link using broker
        $status = Password::broker('members')->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }
}