<?php

namespace App\Observers;

use App\Mail\PropertyUpdated;
use App\Models\LeadAutomation;
use App\Property;
use App\Shortlist;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PropertyObserver
{
    /**
     * Handle the property "created" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function created(Property $property)
    {
        Log::info('Create a new Property');
    }

    /**
     * Handle the property "updated" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function updated(Property $property)
    {
        // If Property Price has changed....
        if($property->price)
        {
            // If Members Area Enabled...
            if(settings('members_area'))
            {
                $original_price = $property->getOriginal('price');

                if($property->price < $original_price)
                {
                    $subject = 'Property Price Change';

                    // Price Reduction (Notify Any User(s) of Change in Price)...
                    $shortlisted_users = Shortlist::where('property_id', $property->id)->get();

                    // Work out Percentage Change...
                    $percent_change = (1 - $property->price / $original_price) * 100;
                    $percent_change = round($percent_change, 0);

                    if($shortlisted_users->count() > 0)
                    {
                        foreach($shortlisted_users as $user)
                        {
                            // Now Find The Lead...
                            $lead = LeadAutomation::where('user_id', $user->user_id)
                                ->where('lead_type', 'shortlist')
                                ->where('lead_is_subscribed', 'y')
                                ->first();

                            if($lead)
                            {
                                // Send Email to The Lead...
                                Mail::to($user->user->email)->send(new PropertyUpdated($property, $lead, $subject));

                                // Now Update The Last Contacted...
                                $lead_automation = LeadAutomation::find($lead->lead_automation_id);
                                $lead_automation->last_contacted = Carbon::now();
                                $lead_automation->save();
                            }

                        }
                    }
                }
            }
        }
    }

    /**
     * Handle the property "deleted" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function deleted(Property $property)
    {
        //
    }

    /**
     * Handle the property "restored" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function restored(Property $property)
    {
        //
    }

    /**
     * Handle the property "force deleted" event.
     *
     * @param  \App\Property  $property
     * @return void
     */
    public function forceDeleted(Property $property)
    {
        //
    }
}
