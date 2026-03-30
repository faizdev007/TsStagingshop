<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PortalAutoResponseMail extends Mailable
{
    use Queueable, SerializesModels;

    public $property;
    public $source;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($property, $source, $name)
    {
        $this->subject = 'Thank you for your enquiry '. $name .'!';
        $this->property = $property;
        $this->source = $source;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.portalresponse')
                ->with(
                    [
                        'property' =>  $this->property,
                        'source'    => $this->source,
                        'name'      => $this->name
                    ]
                )
                ->subject($this->subject);
    }
}
