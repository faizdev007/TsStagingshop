<?php

namespace App\Mail;

use App\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SubscribeMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The content instance.
     *
     * @var content
     */
    public $subscribers;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Subscriber $subscribers)
    {
        $this->subscribers = $subscribers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = 'New subscriber from '.$this->subscribers->email;

        return $this
            ->subject($subject)
            ->view('emails.subscribe')->with(['subscriber'=>$this->subscribers]);
    }
}
