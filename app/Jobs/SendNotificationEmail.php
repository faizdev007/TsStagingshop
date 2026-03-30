<?php

namespace App\Jobs;

use App\Mail\NewMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNotificationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $receiever;
    /**
     * Create a new job instance.
     */
    public function __construct($receiever)
    {
        //
        $this->receiever = $receiever;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        // Send Message to User To Notify Them of a New Message....
        Mail::to($this->receiever->email)->send(new NewMessage());
    }
}
