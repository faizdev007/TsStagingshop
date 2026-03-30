<?php

namespace App\Mail;

use App\Property;
use App\PropertyAlert;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PropertyAlertCronMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The content instance.
     *
     * @var content
     */
    public $propertyalert,$properties,$alert;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($properties, $alert)
    {
        $this->properties = $properties;
        $this->alert = $alert;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = 'Property Alert | '.settings('site_name');

        return $this
            ->subject($subject)
            ->view('emails.property-alert.base')->with(['properties'=>$this->properties, 'alert'=>$this->alert]);
    }
}
