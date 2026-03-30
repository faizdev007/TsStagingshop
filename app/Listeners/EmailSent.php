<?php

namespace App\Listeners;

use App\Models\EmailValuationMessage;
use App\Models\LeadAutomation;
use App\Models\LeadAutomationMessage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use jdavidbakr\MailTracker\Events\EmailSentEvent;

class EmailSent
{
    public function __construct()
    {

    }

    public function handle(EmailSentEvent $event)
    {
        $automation_id = $event->sent_email->getHeader('X-Lead-ID');

        // Find The Automation...
        $automation = LeadAutomation::find($automation_id);

        // Create Automation Messages Record....
        if(isset($automation_id))
        {
            $automation_message = new LeadAutomationMessage;
            $automation_message->lead_id = $automation->lead_id;
            $automation_message->automation_id = $automation_id;
            $automation_message->message_id = $event->sent_email->id;
            $automation_message->save();
        }

        // Track Valuation Emails....
        $valuation_id = $event->sent_email->getHeader('X-Valuation-ID');

        if(isset($valuation_id))
        {
            $valuation_message = new EmailValuationMessage;
            $valuation_message->client_valuation_id = $valuation_id;
            $valuation_message->message_id = $event->sent_email->id;
            $valuation_message->save();
        }

        // Useful For DEBUG.... Log::info(print_r($event, true));
    }
}