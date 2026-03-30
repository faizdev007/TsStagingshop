<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class PropertyLeadMail extends Mailable
{
    use Queueable, SerializesModels;

    public $property;
    public $properties;
    public $lead;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($property, $properties, $lead)
    {
        $this->property = $property;
        $this->properties = $properties;
        $this->lead = $lead;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.leads.property')
            ->withSwiftMessage(function ($message)
            {
                // Track This Email...
                $message->getHeaders()->addTextHeader('X-Lead-ID', $this->lead->lead_automation_id);
            })
            ->subject('Your property interest')
            ->with(
                [
                    'lead'          => $this->lead,
                    'property'      => $this->property,
                    'properties'    => $this->properties
                ]
            );
    }
}
