<?php

namespace App\Jobs;

use App\Mail\PropertyLeadMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPropertyLeadEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $property, $similar_properties, $lead;
    /**
     * Create a new job instance.
     */
    public function __construct($property, $similar_properties, $lead)
    {
        //
        $this->property = $property;
        $this->similar_properties = $similar_properties;
        $this->lead = $lead;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Mail::to($this->lead->lead->email)->send(new PropertyLeadMail($this->property, $this->similar_properties, $this->lead));
    }
}
