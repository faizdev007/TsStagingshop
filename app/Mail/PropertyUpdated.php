<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PropertyUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $property;
    public $lead;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($property, $lead, $subject)
    {
        $this->property = $property;
        $this->lead = $lead;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.property-alert.property_change') // FYI - Change this View (FRIDAY)....
            ->withSwiftMessage(function ($message)
            {
                // Track This Email...
                $message->getHeaders()->addTextHeader('X-Lead-ID', $this->lead->lead_automation_id);
            })
            ->subject($this->subject)
            ->with(
                [
                    'lead'          => $this->lead,
                    'property'      => $this->property,
                ]
            );
    }
}
