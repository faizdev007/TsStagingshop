<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendModalQueryEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $enquiry,$tomailId;
    /**
     * Create a new job instance.
     */
    public function __construct($enquiry,$tomailId)
    {
        //
        $this->enquiry = $enquiry;
        $this->tomailId = Config::get('mail.from.address');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $data = $this->enquiry;
        $to = $this->tomailId;
        //
        // Send email
        Mail::send('emails.property-inquiry', ['enquiry' => $data], function ($message) use ($to, $data) {
            $message->to($to)
                ->subject('New Property Inquiry from ' . $data->fullname . ' ' . $data->lastname);
        });
    }
}
