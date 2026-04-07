<?php

namespace App\Jobs;

use App\Mail\EnquiryMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendAdminEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $enquiry, $tomailId;

    public function __construct($enquiry)
    {
        $this->enquiry = $enquiry;
        $this->tomailId = Config::get('mail.from.address');
    }

    public function handle()
    {
        Mail::to($this->tomailId)
            ->send(new EnquiryMail($this->enquiry));
    }
}
