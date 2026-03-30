<?php

namespace App\Mail;

use Config;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Str;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The content instance.
     *
     * @var content
     */
    public $subject, $msg;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $msg)
    {
        $this->subject = $subject;
        $this->msg = $msg;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = $this->subject;

        return $this
            ->withSwiftMessage(function ($message)
            {
                // Do Not Track This Email...
                $message->getHeaders()->addTextHeader('X-No-Track',Str::random(10));
            })
            ->subject($subject)
            ->view('emails.generic')->with(['msg'=>$this->msg ]);
    }
}
