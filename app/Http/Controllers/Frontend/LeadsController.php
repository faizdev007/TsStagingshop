<?php

namespace App\Http\Controllers\Frontend;

use App\Mail\PropertyLeadMail;
use App\Models\LeadAutomation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\SendPropertyLeadEmail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class LeadsController extends Controller
{
    /**
     * Send Lead Automation(s)...
     */

    public function send_lead_automations()
    {
        $days_ago = Carbon::now()->subWeek();
        //OR ->subDays(days);

        // Find Anyone who was last contacted 7 days ago...
        $leads = LeadAutomation::where('last_contacted', '<=', $days_ago )
            ->where('lead_is_subscribed', 'y')
            ->get();

        foreach($leads as $lead)
        {
            if($lead->lead_type == 'property')
            {
                // Send User Some Related Properties...
                $property = $lead->lead->property;

                $price_range = ($property->price - ($property->price * .1)).'-'.($property->price + ($property->price * .1));
                $similar_criteria = array
                (
                    'exclude_id' => json_encode($property->id),
                    'price_range' => $price_range,
                    'property_type_id' => $property->property_type_id
                );

                $similar_properties = $property->searchWhere($similar_criteria, FALSE, 6); // 6 Similar Properties....

                SendPropertyLeadEmail::dispatch($property, $similar_properties, $lead);
                
                // Now Update The Last Contacted...
                $lead_automation = LeadAutomation::find($lead->lead_automation_id);
                $lead_automation->last_contacted = Carbon::now();
                $lead_automation->save();

                echo "Sent!";
            }
        }
    }

    public function unsubscribe(Request $request)
    {
        $lead = LeadAutomation::find($request->input('id'));
        $lead->lead_is_subscribed = 'n';
        $lead->save();

        $data = [ 'success' => 'Successfully Unsubscribed!' ];
        return redirect(url('unsubscribe'))->with($data);
    }
}
