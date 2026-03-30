<?php

namespace App\Jobs;

use App\Mail\ValuationMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendValuationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $valuation, $testimonial;
    /**
     * Create a new job instance.
     */
    public function __construct($valuation, $testimonial)
    {
        //
        $this->valuation = $valuation;
        $this->testimonial = $testimonial;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Mail::to($this->valuation->client->client_email)
            ->send(new ValuationMessage($this->valuation, $this->testimonial));
    }
}
