<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PropertySearchMail extends Mailable
{
    use Queueable, SerializesModels;

    public $properties;
    public $lead;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($properties, $lead)
    {
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
        return $this->view('emails.leads.search')
            ->withSwiftMessage(function ($message)
            {
                // Not Track This Email...
                $message->getHeaders()->addTextHeader('X-Lead-ID', $this->lead->lead_automation_id);
            })
            ->subject('Your property search updates')
            ->with(
                [
                    'properties'    => $this->properties,
                    'lead'          => $this->lead
                ]
            );
    }
}
