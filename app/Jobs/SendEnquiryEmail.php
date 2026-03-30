<?php

namespace App\Jobs;

use App\Mail\EnquiryMail;
use App\Mail\GenericMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEnquiryEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $enquiry,$msg,$mailID;

    public function __construct($enquiry,$msg=null,$mailID=null)
    {
        $this->enquiry = $enquiry;
        $this->msg = $msg;
        $this->mailID = $mailID;
    }

    public function handle()
    {
        if($this->msg != null){
            $subject = $this->msg['sub'];
            $message = $this->msg['msg'];
            Mail::to($this->mailID ?? $this->enquiry->email)
                ->send(new GenericMail($subject, $message));
        }else{
            Mail::to($this->enquiry->email)
                ->send(new EnquiryMail($this->enquiry));
        }
    }
}
