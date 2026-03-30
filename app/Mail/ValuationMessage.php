<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class ValuationMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $valuation;
    public $testimonial;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($valuation, $testimonial)
    {
        $this->valuation = $valuation;
        $this->testimonial = $testimonial;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.valuation.report')
            ->withSwiftMessage(function ($message)
            {
                // Track This Email...
                $message->getHeaders()->addTextHeader('X-Valuation-ID', $this->valuation->client_valuation_id);
            })
            ->subject('Your valuation report')
            ->with(
                [
                    'valuation'     => $this->valuation,
                    'testimonial'   => $this->testimonial
                ]
            );
    }
}
