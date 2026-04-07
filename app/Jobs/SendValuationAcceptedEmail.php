<?php

namespace App\Jobs;

use App\Mail\ValuationAccepted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SendValuationAcceptedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $valuation;
    /**
     * Create a new job instance.
     */
    public function __construct($valuation)
    {
        //
        $this->valuation = $valuation;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //

        // Send Accepted Valuation to Site Admin....
        Mail::to(Config::get('mail.from.address'))
            ->send(new ValuationAccepted($this->valuation));
    }
}
