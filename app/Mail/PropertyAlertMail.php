<?php

namespace App\Mail;

use App\PropertyAlert;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PropertyAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The content instance.
     *
     * @var content
     */
    public $propertyalert;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(PropertyAlert $propertyalert)
    {
        $this->propertyalert = $propertyalert;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = 'New property alert from '.$this->propertyalert->email;

        return $this
            ->subject($subject)
            ->view('emails.propertyalert')->with(['propertyalert'=>$this->propertyalert]);
    }
}
