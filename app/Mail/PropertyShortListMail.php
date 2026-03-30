<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PropertyShortListMail extends Mailable
{
    use Queueable, SerializesModels;

    public $properties;
    public $similar_properties;
    public $shortlist;
    public $lead;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($properties, $similar_properties, $shortlist, $lead)
    {
        $this->properties = $properties;
        $this->similar_properties = $similar_properties;
        $this->shortlist = $shortlist;
        $this->lead = $lead;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.leads.shortlist')
            ->withSwiftMessage(function ($message)
            {
                // Not Track This Email...
                $message->getHeaders()->addTextHeader('X-Lead-ID', $this->lead->lead_automation_id);
            })
            ->subject('Your property shortlist updates')
            ->with(
                [
                    'properties'    => $this->properties,
                    'similar'       => $this->similar_properties,
                    'shortlist'     => $this->shortlist,
                    'lead'          => $this->lead
                ]
            );
    }
}
