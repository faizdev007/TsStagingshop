<?php

namespace App\Jobs;

use App\Mail\SubscribeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSubscribeEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $subscribers, $email;
    /**
     * Create a new job instance.
     */
    public function __construct($email,$subscribers)
    {
        //
        $this->email = $email;
        $this->subscribers = $subscribers;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Mail::to($this->email)
            ->send(new SubscribeMail($this->subscribers));
    }
}
