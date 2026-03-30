<?php

namespace App\Console\Commands;

use App\Mail\PropertyLeadMail;
use App\Mail\PropertySearchMail;
use App\Mail\PropertyShortListMail;
use App\Models\LeadAutomation;
use App\Models\SaveSearch;
use App\Property;
use App\PropertyAlert;
use App\Shortlist;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class LeadEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:lead';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a lead automation Email to the customer (Sends every Wednesday at 10am)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $days_ago = Carbon::now()->subWeek();
        //OR ->subDays(days);

        // Find Anyone who was last contacted 7 days ago...
        $collection = LeadAutomation::where('last_contacted', '<=', $days_ago )
            ->where('lead_is_subscribed', 'y')
            ->where('lead_contact_type', 'email')
            ->get();

        // Make Unique User ID - Prevent sending Multiple to that user....
        $leads = $collection->unique('user_id');

        if($leads->count() > 0)
        {
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

                    // Only send Email if we have Similar Properties....
                    if($similar_properties->count() > 0)
                    {
                        // If Relationship Exists...
                        if($lead->lead)
                        {
                            Mail::to($lead->lead->email)->send(new PropertyLeadMail($property, $similar_properties, $lead));

                            // Now Update The Last Contacted...
                            $lead_automation = LeadAutomation::find($lead->lead_automation_id);
                            $lead_automation->last_contacted = Carbon::now();
                            $lead_automation->save();
                        }
                    }
                }
                if($lead->lead_type == 'shortlist')
                {
                    // Shortlist Properties Email....

                    // Get Properties in the User(s) - Shortlist...
                    $shortlist = Shortlist::where('user_id', $lead->user_id)->first();

                    if($shortlist)
                    {
                        $properties = $shortlist->with('property')->get()->pluck('property');

                        // If they have properties, Send out an Email with Info on their properties and send similar...
                        if($properties->count() > 0)
                        {
                            // Sort Property Collection by Price...
                            $sort_by_price = $properties->sortBy('price');

                            // Now get the Price(s)....
                            $prices = $properties->pluck('price');
                            $num_prices = count($prices);

                            // Set a Price Range...
                            $price_range = ($prices[0] - ($prices[0] * .1)).'-'.($prices[$num_prices -1] + ($prices[$num_prices -1] * .1));

                            // Exclude Current Props..
                            $current_ids = $properties->pluck('id');

                            $similar_criteria = array
                            (
                                'exclude_ids' => $current_ids,
                                'price_range' => $price_range
                            );

                            // Get Similar Properties...
                            $property = new Property();
                            $similar_properties = $property->searchWhere($similar_criteria, FALSE, 6); // 6 Similar Properties....

                            // Send Mail
                            // If Relationship Exists...
                            if($lead->lead)
                            {
                                Mail::to($shortlist->user->email)->send(new PropertyShortListMail($properties, $similar_properties, $shortlist, $lead));
                            }

                            // Now Update The Last Contacted...
                            $lead_automation = LeadAutomation::find($lead->lead_automation_id);
                            $lead_automation->last_contacted = Carbon::now();
                            $lead_automation->save();
                        }
                    }
                }
                if($lead->lead_type == 'search')
                {
                    // Only Send Saved Search Email, If there's 0 Property Alerts...
                    $property_alerts = PropertyAlert::where('user_id', $lead->user_id)->count();

                    if($property_alerts == 0)
                    {
                        if($lead->user->count() > 0)
                        {
                            // Find User(s) Saved Searches...
                            $saved_searches = SaveSearch::where('user_id', $lead->user->id)->get();

                            if($saved_searches->count() > 0)
                            {
                                foreach($saved_searches as $saved_search)
                                {
                                    // Convert Criteria to Array...
                                    $search_criteria = json_decode($saved_search->saved_search_criteria, true);

                                    // Add to Criteria in the last Seven Days...
                                    $search_criteria["sevenDays"] = true;

                                    // Get Properties from Search....
                                    $property = new Property();
                                    $properties = $property->searchWhere($search_criteria, FALSE, 6);

                                    if($properties->count() > 0)
                                    {
                                        if($lead->user)
                                        {
                                            Mail::to($lead->user->email)->send(new PropertySearchMail($properties, $lead));

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
            }
        }
    }
}
