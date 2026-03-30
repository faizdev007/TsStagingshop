<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOTPEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $otpdata,$userdata;

    /**
     * Create a new job instance.
     */
    public function __construct($otpdata,$userdata)
    {
        //
        $this->otpdata = $otpdata;
        $this->userdata = $userdata;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        $otp = $this->otpdata;
        $user = $this->userdata;
        // Send OTP email
        Mail::send('emails.login-otp', ['otp' => $otp], function($message) use ($user) {
            $message->to($user->email)
                    ->subject('Login Verification Code');
        });
    }
}
