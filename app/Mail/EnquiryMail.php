<?php

namespace App\Mail;

use App\Enquiry;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class EnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The content instance.
     *
     * @var content
     */
    public $enquiry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Enquiry $enquiry)
    {
        $this->enquiry = $enquiry;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        switch ($this->enquiry->category) {
            case 'Property Enquiry (Sidebar)':
            case 'Property Enquiry (Bottom)':
                $subject = 'New property enquiry from '.$this->enquiry->name.' for Ref: '.$this->enquiry->ref;
                break;

            case 'Valuation':
                $subject = 'New request for valuation from '.$this->enquiry->name;
                break;

            default:
                $subject = 'New enquiry from '.$this->enquiry->name;
                break;
        }

        return $this
            ->withSwiftMessage(function ($message)
            {
                // Do Not Track This Email...
                $message->getHeaders()->addTextHeader('X-No-Track',Str::random(10));
            })
            ->subject($subject)
            ->view('emails.enquiry');
    }
}
