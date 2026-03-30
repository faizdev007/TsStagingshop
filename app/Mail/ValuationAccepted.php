<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class ValuationAccepted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $valuation;

    public function __construct($valuation)
    {
        $this->valuation = $valuation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->withSwiftMessage(function ($message)
            {
                // Do Not Track This Email...
                $message->getHeaders()->addTextHeader('X-No-Track',Str::random(10));
            })
            ->subject('Client Valuation Accepted')
            ->view('emails.valuation.accepted')->with(['valuation'=>$this->valuation ]);
    }
}
