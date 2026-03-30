<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Languages;
use App\Models\Team;
use App\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Page;
use App\PropertyType;
use App\Slide;
use App\User;
use App\Testimonial;
use App\Traits\TemplateTrait;
use App\Models\Community;
use App\Models\Profile;
use App\Models\PropertyClaim;
use App\Models\SaveSearch;
use App\Post;
use App\SearchContent;
use App\PropertyStats;
use Illuminate\Support\Facades\Cache;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PagesController extends Controller
{
    use TemplateTrait;



    public function __construct()
    {
        $this->fillable = array
        (
            'name',
            'in',
            'area',
            'complex_name',
            'for',
            'property_type',
            'property-type',
            'is-brown-harris-stevens',
            'beds',
            'minbeds',
            'baths',
            'price_range',
            'min-price',
            'max-price',
            'category',
            'community',
            'ref',
            'sort',
            'page',
            'complex'

        );
    }

    public function index(Request $request)
    {
        $template = 1;
        $footerTemplate = 1;
        $criteria = $request->input();
        
        // get page content
        $page = Page::where('route', '/')->firstOrFail();

        //metadata
        $meta = get_metadata($page, 'home');

        if(settings('translations'))
        {
            // Query The Polymorphic Relationship to return translated slides...
            $slides = Slide::whereHas('translations', function ($query)
            {
                $query->where('language', LaravelLocalization::getCurrentLocale());
            })->orderBy('priority', 'asc')->get()->toArray();

            // No Translations Found - Return All....
            if($slides->isEmpty())
            {
                $slides = Cache::remember('slides', 3600, function () {
                    return Slide::orderBy('priority', 'ASC')->get()->toArray();
                });
            }
        }
        else
        {
            // get Slides
            $slides = Cache::remember('slides', 3600, function () {
                return Slide::orderBy('priority', 'ASC')->get()->toArray();
            });

        }

        $propertyTypes = PropertyType::PropertyType()->orderBy('id')->get();

        // Define fixed price ranges instead of using range()
        $curr = get_current_currency();
        
        $priceList = [
            '0-1000000' => "{$curr} 0 - {$curr} 1,000,000",
            '1000000-2000000' => "{$curr} 1,000,000 - {$curr} 2,000,000",
            '2000000-5000000' => "{$curr} 2,000,000 - {$curr} 5,000,000",
            '5000000-10000000' => "{$curr} 5,000,000 - {$curr} 10,000,000",
            '10000000-20000000' => "{$curr} 10,000,000 - {$curr} 20,000,000",
            '20000000-50000000' => "{$curr} 20,000,000 - {$curr} 50,000,000",
            '50000000-999999999' => "{$curr} 50,000,000 +"
        ];

        return view('frontend.demo1.index')
               ->with('template', $template)
               ->with('footerTemplate', $footerTemplate)
               ->with('page', $page)
               ->with('meta', $meta)
               ->with('propertyTypes', $propertyTypes)
               ->with('slides', $slides)
               ->with('criteria',$criteria)
               ->with('priceList', $priceList);
    }

    /*
    * Generic page
    *
    */
    public function view(Request $request)
    {
        $route = $request->segment(1); // $request->path();

        if(settings('translations'))
        {
            // Translations Set - Send To Segment(2)...
            $languages = Languages::first();

            if(config('app.locale') !== $languages->language_default)
            {
                $route = $request->segment(2);
            }
        }

        // get page content
        $page = Page::where('route', $route)->firstorFail();

        if($page)
        {
            // If not full width, get four latest properties
            //$properties = Property::latest_properties_for_frontend();

            //metadata
            $meta = get_metadata($page);

            if($page->page_type == 'page')
            {
                if($page->route == 'the-team')
                {
                    if(settings('team_page'))
                    {
                         // get page content
                        $aboutpage = Page::where('route', '/')->firstOrFail();
                        // Get Team Members....
                        $team = Team::orderBy('order', 'asc')->get();

                        return view('frontend.demo1.page', [
                            'route' => $route,
                            'meta'  => $meta,
                            'team'  => $team,
                            'page'  => $page,
                            'aboutpage'=>$aboutpage
                        ]);
                    }
                }

                if($page->route == 'communities')
                {
                    $communities = Community::where([['is_publish', 1]])->orderBy('sequence', 'ASC')->get();
                    return view('frontend.demo1.page', [
                        'route' => $route,
                        'meta'  => $meta,
                        'items'  => $communities,
                        'page'  => $page
                    ]);
                }
                
                $team = Team::orderBy('order', 'asc')->get();
                //page is Temporary
                return view('frontend.demo1.page', [
                    'route' => $route,
                    'meta' => $meta,
                    'page' => $page,
                    'team'  => $team,
                ]);
            }
            else
            {
                $latest_properties = $page->sections->contains('type', 'latest_properties');

                $propertyTypes = propertyType::orderBy('name')->get();

                if($latest_properties)
                {
                    // Set Criteria Town / City
                    $criteria['in'] = $page->route;
                    $criteria['in'] = $page->route;

                    // Look For Properties In Given Town....
                    $property = new Property();
                    $properties = $property->searchWhere($criteria);

                    if(empty($properties))
                    {
                        // Just Select Latest 5...
                        $properties = Property::limit(5);
                    }
                }
                else
                {
                    $properties = null;
                }

                // See If We Have a Bespoke Page view (If Not - Use Shared)...
                $view = 'frontend.demo1.page-templates.bespoke';

                if(view()->exists($view))
                {
                    return view($view, [
                        'route'             => $route,
                        'meta'              => $meta,
                        'latest_properties' => $properties,
                        'propertyTypes'     => $propertyTypes,
                        'page'              => $page
                    ]);
                }
                else
                {
                    // Shared View...
                    return view('frontend.shared.page-templates.bespoke', [
                        'route'             => $route,
                        'meta'              => $meta,
                        'latest_properties' => $properties,
                        'propertyTypes'     => $propertyTypes,
                        'page'              => $page
                    ]);
                }
            }
        }
    }

    /*
    * Contact page
    *
    */
    public function contact(Request $request)
    {
        $route = $request->segment(1); // $request->path();

        if(settings('translations'))
        {
            // Translations Set - Send To Segment(2)...
            $languages = Languages::first();

            if(config('app.locale') !== $languages->language_default)
            {
                $route = $request->segment(2);
            }
        }

        // get page content
        $page = Page::where('route', $route)->firstOrFail();

        //metadata
        $meta = get_metadata($page);

        return view('frontend.demo1.page', [
            'route' => $route,
            'meta' => $meta,
            'page' => $page
        ]);
    }

    /*
    * Valuation page
    *
    */
    public function valuation()
    {
        // get page content
        $page = Page::where('route', 'valuation')->firstOrFail();

        //metadata
        $meta = get_metadata($page);
        $propertyTypes = propertyType::PropertyType()->orderBy('id')->get();
        return view('frontend.demo1.page', [
            'route' => 'valuation',
            'meta' => $meta,
            'page' => $page,
            'propertyTypes'=>$propertyTypes
        ]);
    }

    /*
    * Testimonials page
    *
    */
    public function testimonials()
    {
        // get testimonials
        $testimonials = Testimonial::orderBy('priority', 'ASC')->paginate(9);

        // dd($testimonials);

        $page = Page::where('route', 'testimonials')->firstOrFail();

        //metadata
        $meta = get_metadata($page);

        return view('frontend.demo1.page', [
            'route' => 'testimonials',
            'meta' => $meta,
            'page' => $page,
            'testimonials' => $testimonials
        ]);
    }

    public function set_language(Request $request)
    {
        $language = $request->input('language');
        $language_settings = Languages::first();
        $request->session()->put('language', $request->input('language'));

        return response()->json('saved');
    }


    public function agent_profile(Request $request, $slug){
        $page = Team::where('team_member_slug',$slug)->first();
        
        $user = User::where('id',$page->user_id)->first();

        $meta = get_metadata($page,'profile');

        $propertiesdata = Property::with(['propertyMediaPhotos'])
        ->whereNotIn('status',[-1])->orderBy('updated_at','desc');

        switch($request->sortvalue){
            case 'lowest-price':
                $properties = $propertiesdata->orderBy('price','asc');
                break;
            case 'most-recent':
                $properties = $propertiesdata->orderBy('updated_at','desc');
                break;
            case 'highest-price':
                $properties = $propertiesdata->orderBy('price','desc');
                break;
            case 'name':
                $properties = $propertiesdata->orderBy('name','asc');
                break;
            default:
                $properties = $propertiesdata->orderBy('updated_at','desc');
                break;
        }

        $criteria = prepare_criteria($request, $this->fillable);

        // Clone before each usage
        $plist  = clone $propertiesdata;   // Available properties
        $psale  = clone $propertiesdata;   // Only sale
        $prent  = clone $propertiesdata;   // Only rent

        $claimproperties  = PropertyClaim::with('property')->where('user_id',$user->id)->where('property_claim_approved',1);
        
        $rentdata = (clone $claimproperties)->whereHas('property', function ($q) {
                        $q->where('is_rental', 1);
                    });

        $selldata = (clone $claimproperties)->whereHas('property', function ($q) {
                        $q->where('is_rental', 0);
                    });

        $counts = [
            'CloseRentDeal'=> $rentdata->count(),
            'CloseSaleDeal'=> $selldata->count(),
            'sold'         => (clone $claimproperties)->count(),
            'sale'         => (clone $psale)->whereNotIn('status',[16,17])->where('is_rental', '!=', 1)->count(),
            'rent'         => (clone $prent)->whereNotIn('status',[16,17])->where('is_rental', 1)->count(),
            'total_RentDeals' => PropertyClaim::gettotalvalue($rentdata),
            'total_saleDeals' => PropertyClaim::gettotalvalue($selldata),
            'total_deals' => PropertyClaim::gettotalactualvalue((clone $claimproperties))
        ];
        $communities = [];
        if($page->team_member_experties){
            $communities = Community::whereIn('id',$page->team_member_experties)->orderByRaw('FIELD(id, '.implode(',', $page->team_member_experties).')')->get();
        }
        $properties = $plist->whereNotIn('is_rental',[1])->paginate(9, ['*'], 'properties_page');
   
        $selllist = $claimproperties->orderBy('property_provide_date', 'desc')->paginate(10, ['*'], 'trackrecord_page');

        return response()->view('frontend.demo1.page-templates.agent-profile', compact('meta','user','page','criteria','properties','communities','selllist','counts'));
    }
}
