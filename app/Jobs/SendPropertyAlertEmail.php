<?php

namespace App\Jobs;

use App\Mail\PropertyAlertCronMail;
use App\Mail\PropertyAlertMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendPropertyAlertEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $properties, $alert;

    /**
     * Create a new job instance.
     */
    public function __construct($properties, $alert=null)
    {
        //
        $this->properties = $properties;
        $this->alert = $alert;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        if($this->alert != null){
            Mail::to( $this->alert->email )->send(new PropertyAlertCronMail($this->properties, $this->alert));
        }else{
            // Config::get('mail.from.address')
            Mail::to('faizdev007@gmail.com')->send(new PropertyAlertMail($this->properties));
        }
    }
}
