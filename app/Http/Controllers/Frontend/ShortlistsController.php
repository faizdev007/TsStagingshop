<?php

namespace App\Http\Controllers\Frontend;

use App\Enquiry;
use App\Models\LeadAutomation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Shortlist;
use App\Property;
use App\PropertyType;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

class ShortlistsController extends Controller
{
    private $members;
    private $user_id;

    public function __construct()
    {
        // See If Members Area is Enabled....
        $this->members = settings('members_area');

        $this->fillable = array(
            'in',
            'for',
            'property_type',
            'beds',
            'price_range',
            'ref',
            'sort',
            'page'
        );

        $this->middleware(function ($request, $next)
        {
            if(Auth::user())
            {
                $this->user_id = Auth::user()->id;
            }
            else
            {
                // Members area enabled...
                if($this->members == '1')
                {
                    if($request->ajax())
                    {
                        $path = $request->path();

                        if($path == 'shortlist/ajax/total')
                        {
                            // Always Return Total for Shortlist....
                            $this->total($request);
                        }
                        else
                        {
                            // Store Property ID In Session...
                            $request->session()->put('property_id', $request->input('property_id'));

                            // Send JSON Response to go to Login...

                            // Warn - Need to login....
                            $request->session()->flash('message_warning', 'Please login or Register first!');

                            $referrer = $request->server('HTTP_REFERER');

                            if(isset($referrer))
                            {
                               // Put Referrer Into Session for Login...
                                $request->session()->put( 'redirect.url', $referrer );
                            }

                            return response()
                                ->json(
                                    [
                                        'url' => url('register')
                                    ]
                                );
                        }
                    }
                }
            }

            return $next($request);

        });
    }

    public function index(Request $request)
    {
        if(settings('members_area') == '1')
        {
            return redirect('account');
        }
        else
        {
            $template = 'shortlist';

            // Metadata
            $meta_data = new \stdClass();
            $meta_data->title = settings('default_location').' Property Shortlist';
            $meta_data->description = 'All your favourite '.settings('default_location').' properties all in one convenient place with the '.settings('site_name').' property shortlist. Find your dream luxury property today.';
            $meta = get_metadata($meta_data);

            $propertyTypes = propertyType::orderBy('name')->get();

            $criteria = prepare_criteria($request, $this->fillable);

            if (empty($criteria['property_type_id']) && !empty($criteria['property_type']))
            {
                $property_type = propertyType::findBySlug($criteria['property_type']);

                if ($property_type)
                {
                    $criteria['property_type_id'] = $property_type->id;
                    $criteria['property_type_name'] = $property_type->name;
                }
            }

            //$properties = Property::latest()->paginate($limit);
            $property = new Property();
            $properties = $property->searchWhere($criteria);

            // Footer template
            if( $request->input('template') == 1)
            {
                $footerTemplate = 2;
            }
            else
            {
                $footerTemplate = 1;
            }

            // If Search is different for template, use template specific search...
            $view = 'frontend.demo1.search';

            if(view()->exists($view))
            {
                return view($view, [ // Shared View in the Templates...
                    'template' => $template,
                    'footerTemplate' => $footerTemplate,
                    'meta' => $meta,
                    'criteria' => $criteria,
                    'propertyTypes' => $propertyTypes,
                    'properties' => $properties,
                    'search_content' => ''
                ]);
            }
            else
            {
                // Load Shared View...
                return view('frontend.shared.search', [ // Shared View in the Templates...
                    'template' => $template,
                    'footerTemplate' => $footerTemplate,
                    'meta' => $meta,
                    'criteria' => $criteria,
                    'propertyTypes' => $propertyTypes,
                    'properties' => $properties,
                    'search_content' => ''
                ]);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax())
        {
            $shortlist = new Shortlist();
            $property_id = $request->input('property_id');

            //CHECK IF EXIST
            $check_exist = $shortlist->check_exist($property_id);
            
            
            // See if a Lead Already Exists...
            $lead_exists = LeadAutomation::where('lead_type', 'shortlist')
                ->where('lead_is_subscribed', 'y')
                ->where('user_id', $this->user_id)
                ->first();

            if($check_exist)
            {
                if($this->members == '1' && $this->user_id)
                {
                    $json['total'] = $shortlist->remove($property_id, $this->user_id);;
                    $json['flag'] = 0;

                }
                else
                {
                    //Members Area Not Enabled - REMOVE SHORTLIST Normally (Via IP Address)...
                    $json['total'] = $shortlist->remove($property_id);
                    $json['flag'] = 0;
                }
            }
            else
            {
                if($this->members == 1 && isset($this->user_id))
                {
                    // Members Area Enabled - ADD SHORTLIST
                    $json['total'] = $shortlist->add($property_id, $this->user_id);
                    $json['flag'] = 1;

                    if(!$lead_exists)
                    {
                        // Create a new lead Category = Property Shortlist...
                        $lead = new Enquiry;
                        $lead->category = 'Automated';
                        $lead->name = Auth::user()->name;
                        $lead->email = Auth::user()->email;
                        $lead->telephone = Auth::user()->telephone;
                        $lead->message = "System Automated Shortlist message";
                        $lead->url = request()->headers->get('referer');
                        $lead->read_at = Carbon::now();
                        $lead->save();

                        // Make Lead Automation...
                        $lead_automation = new LeadAutomation;
                        $lead_automation->lead_id = $lead->id;
                        $lead_automation->lead_type = "shortlist";
                        $lead_automation->lead_is_subscribed = 'y';
                        $lead_automation->lead_contact_type = 'email';
                        $lead_automation->last_contacted = Carbon::now();
                        $lead_automation->user_id = $this->user_id;
                        $lead_automation->save();
                    }
                }
                else
                {
                    // Members Area Not Enabled - ADD SHORTLIST Normally (Add IP Address)...
                    $json['total'] = $shortlist->add($property_id);
                    $json['flag'] = 1;
                }
            }

            return response()->json($json);

        }
    }

    public function total(Request $request)
    {
        if($request->ajax())
        {
            if($this->members == 1 && isset($this->user_id))
            {
                $shortlist = new Shortlist();
                $json['total'] = $shortlist->total($this->user_id);
            }
            else
            {
                // Members Area Not Enabled, Return Shortlist based on IP...
                $shortlist = new Shortlist();
                $json['total'] = $shortlist->total();
            }

            return response()->json($json);
        }
    }

    public function get_user_shortlist($user_id)
    {
        $shortlist = Shortlist::where('user_id', $user_id)->get();
        return $shortlist->toJson();
    }


}
