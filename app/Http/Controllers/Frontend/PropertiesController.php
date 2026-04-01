<?php

namespace App\Http\Controllers\Frontend;
use Session;
use App\Models\Category;
use App\Enquiry;
use App\Models\LeadAutomation;
use App\Models\SaveSearch;
use App\Post;
use App\SearchContent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Property;
use App\PropertyType;
use App\PropertyStats;
use App\Page;
use App\Traits\TemplateTrait;
use App\Traits\PropertyBaseTrait;
use App\Traits\HelperTrait;
use App\Models\Community;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Validator;

class PropertiesController extends Controller
{

    use TemplateTrait;
    use PropertyBaseTrait;
    use HelperTrait;
    protected $user_id;

     /**
     * Create a new controller instance.
     *
     * @return void
     */
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

        if(Auth::user())
        {
            $this->user_id = Auth::user()->id;
        }
    }

    /*
    * Search result page
    * Search requests come here
    * POST method - handle search
    */
    public function search(Request $request, $return = false)
    {
        // We need it to take the $request and give us a GET URL to redirect to
        //[PROPERTY TYPE]-[FOR-SALE or TO-RENT]/in/[SEARCHED-LOCATION]/from/[MIN PRICE]/to/[MAX PRICE]/beds/[MIN BEDS]/ref/[REFERENCE NUMBER]/page/[PAGE NUMBER]
        $criteria = $request->input();

        $redirect = 'property-for-sale';
        
        $propertyType = 'property';

        if (!empty($criteria['property_type'])) {
            $propertyType = $criteria['property_type'];
            unset($criteria['property_type']);
        }

        if (!empty($criteria['status'])) {
            $propertyType = $criteria['status'];
            unset($criteria['status']);
        }
      
        
        if (!empty($criteria['for'])) {
            $for_slug = ($criteria['for'] == 'rent') ? 'to-rent' :  'for-sale';
            $for = $propertyType . '-' . $for_slug;
            unset($criteria['for']);
        }

        if(!empty($criteria['in']))
        {
          $criteria['in'] = trim(strtolower($criteria['in']));
        }

        if(!empty($criteria['area']))
        {
          $criteria['area'] = $criteria['area'];
        }

        if(!empty($criteria['complex']))
        {
          $criteria['complex'] = $criteria['complex'];
        }

        if(!empty($criteria['min_price']) )
        {
            $criteria['min-price'] = $criteria['min_price'];
        }

        if(!empty($criteria['max_price']))
        {
            $criteria['max-price'] = $criteria['max_price'];
        }

        $criteria = array_map('urldecode', array_filter(array_intersect_key($criteria, array_flip($this->fillable))));
        $criteria = http_build_query($criteria, '', '/');
        $criteria = str_replace('=','/',$criteria);
        $redirect = $for.'/'.$criteria;

        if($return)
        {
            return $redirect;
        }
        else
        {
            return redirect($redirect);
        }
    }

    public function search_map()
    {
        $propertyTypes = propertyType::PropertyType()->orderBy('id')->get();
        return view('frontend.demo4.page-templates.search-map')
            ->with('propertyTypes', $propertyTypes);
    }

 

    public function by_category(Request $request, $cat)
    {
        $page = '';


        if($request->segment(3) == 'sort'){
            $request->merge([
                'sort' => $request->segment(4),
            ]);
        }

        $criteria = prepare_criteria($request, $this->fillable);

        if($cat == 'character-features')
        {
            $page = Page::where('route', 'character-features')->firstOrFail();
        }

        if(
            $cat == 'vacation-rentals' ||
            $cat == 'commercial-rent-property' ||
            $cat == 'residential-rent-properties'
        ){
            $criteria['for'] = 'rent';
        }else{
            $criteria['for'] = 'sale';
        }

        //Remap property types
        $cat = remapPropertyTypes($cat);
        $property_type_flag = propertyType::PropertyType()->where('slug',$cat)->orderBy('name')->first();
        $ptype_id = get_ptype_id_by_slug($cat);

        if(!empty($ptype_id)){
            if(!empty($property_type_flag)){
                $criteria['property-type-ids'] = array($ptype_id);
                $criteria['property-type-slugs'] = array($cat);
            }else{
                $criteria['category'] = $cat;
                $criteria['category-ids'] = array($ptype_id);
                $criteria['category-slugs'] = array($cat);
            }
        }else{
            if(!empty($property_type_flag)){
                $criteria['category-ids'] = array(0);
            }else{
                $criteria['property-type-ids'] = array(0);
            }
        }

        if($cat =='brown-harris-stevens'){
            $criteria['is-brown-harris-stevens'] = 'yes';
            unset($criteria['property-type-ids']);
        }

        $property = new Property();
        $properties = $property->searchWhere($criteria);

        $template = 1;
        $footerTemplate = 1;
        $limit = 12;

        // Metadata
        $meta = get_metadata($criteria, 'search');

        // Pass the $request for the search criteria
        $propertyTypes = propertyType::PropertyType()->orderBy('id')->get();

        $view_data = array(
            'template' => $template,
            'footerTemplate' => $footerTemplate,
            'meta' => $meta,
            'criteria' => $criteria,
            'propertyTypes' => $propertyTypes,
            'properties' => $properties,
            'search_content' => null,
            'posts' => null,
            'saved_search' => null,
            'community'=> '',
            'page' => $page,
            'cattype'=>$property_type_flag
        );

        // If Search is different for template, use template specific search...
        $view = 'frontend.demo1.search';

        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view, $view_data);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.search', $view_data);
        }
    }

    /*
    * Search result page
    * GET method
    */
    public function index(Request $request)
    {
        $check_shortlist = $request->segment(1);
        $current = url()->current();

        $setting = settings();

        // If Site is set to Rent - Redirect to Rent....
        if ($setting->get('sale_rent') == 'rent')
        {
            if($check_shortlist == 'property-for-sale')
            {
                return redirect('property-for-rent');
            }
        }

        if ($setting->get('sale_rent') == 'sale')
        {
            if($check_shortlist == 'property-for-rent')
            {
                return redirect('property-for-sale');
            }
        }

        $request->session()->put('latest_search', $current);

        $template = 1;
        $footerTemplate = 1;
        $limit = 12;

        // Pass the $request for the search criteria
        $propertyTypes = propertyType::PropertyType()->orderBy('id')->get();

        // dd($propertyTypes);

        $criteria = prepare_criteria($request, $this->fillable);

        // dd($criteria);

        // Let's just put this here - RH 6/1/19
        if (empty($criteria['property_type_id']) && !empty($criteria['property_type'])) {
            $property_type = propertyType::findBySlug($criteria['property_type']);
            if ($property_type) {
                $criteria['property_type_id'] = $property_type->id;
                $criteria['property_type_name'] = $property_type->name;
            }
        }

        $page = '';
        if( !empty($criteria['category']) && $criteria['category'] == 'luxury-listing')
        {
            $page = Page::where('route', 'luxury-listing')->firstOrFail();
        }

        $community='';
        if(!empty($criteria['community']))
        {
            $community = Community::findByName($criteria['community']);
        }

        // dd($criteria);
        //$properties = Property::latest()->paginate($limit);
        $property = new Property();
        $properties = $property->searchWhere($criteria);

        $sort_property_list = $property->leftJoin(
            'property_types',
            'property_types.id',
            '=',
            'properties.property_type_id'
        )
        ->whereNotIn('properties.status', [1, -1]);

        
        if(!is_null($criteria['for']) && $criteria['for'] == 'rent'){
            $sort_property_list->where('is_rental',1);
        }else{
            $sort_property_list->where('is_rental',0);
        } 


        $sort_property_list = $sort_property_list->select(
            'property_types.name as pname',
            'properties.country',
            'properties.town',
            'properties.city',
            'properties.complex_name',
            'properties.region',
            'properties.street',
            'properties.beds',
            'properties.baths',
            'properties.price'
        );


        if(!empty($criteria['area'])){
            $area = $area = urldecode(trim($criteria['area']));
            $sort_property_list->where(function($sort_property_list) use ( $area )
            {
                $sort_property_list->orwhere([['properties.street', 'LIKE', '%'.$area.'%']]);
                $sort_property_list->orwhere([['properties.town', 'LIKE', '%'.$area.'%']]);
                $sort_property_list->orwhere([['properties.city', 'LIKE', '%'.$area.'%']]);
                $sort_property_list->orwhere([['properties.region', 'LIKE', '%'.$area.'%']]);
                $sort_property_list->orwhere([['properties.complex_name', 'LIKE', '%'.$area.'%']]);
            });
        }

        if (!empty($criteria['complex'])) {
            $sort_property_list->where('complex_name', $criteria['complex']);
        }

        if (!empty($criteria['property_type'])) {
            $sort_property_list->where('property_types.name', $criteria['property_type']);
        }

        if(!empty($criteria['minbeds'])){
           $sort_property_list->where('beds', '>=', $criteria['minbeds']);
        }
        

        $sort_property_list = $sort_property_list->get();

        
        // print_R($properties);
        // dd($properties->first());

        if($check_shortlist == 'shortlist')
        {
            $template = 'shortlist';
            // Metadata
            $meta_data = new \stdClass();
            $meta_data->title = $setting->get('default_location').' Property Shortlist';
            $meta_data->description = 'All your favourite '.$setting->get('default_location').' properties all in one convenient place with the '.settings('site_name').' property shortlist. Find your dream luxury property today.';
            $meta = get_metadata($meta_data);
        }
        else
        {
            // For search page only
            if(count($properties))
            {
                $propertyStats = new PropertyStats();
                $propertyStats->property_search_store($properties, '');
            }
            // Metadata
            $meta = get_metadata($criteria, 'search');
        }

        // Current URL...
        $current_url = url()->current();

        // Look for Search Content Matches...
        $search_content = SearchContent::where('content_url', $current_url)->first();

        // Look For Tagged Articles For This Location...
        if(isset($criteria['in']))
        {
            $posts = Post::whereHas('tags', function ($query) use ($criteria)
            {
                $query->where('post_tag_value', $criteria['in']);
            }
            )->get();
        }
        else
        {
            $posts = NULL;
        }

        // If Search is different for template, use template specific search...
        $view = 'frontend.demo1.search';

        // Members Area (Logic to show if search has been saved, or not)....
        if($setting->get('members_area') == 1)
        {
            // Look for existing searches, based on the request()->path e.g (property-for-sale/in/seaton+carew)
            $saved_search = SaveSearch::where('saved_search_url', request()->path())->first();
        }
        else
        {
            $saved_search = null;
        }

        // With this safer code:
        $curr = get_current_currency();
        $curr = (!empty($curr)) ? $curr : 'AED';

        $priceList = [
            '0-1000000' => "{$curr} 0 - {$curr} 1,000,000",
            '1000000-2000000' => "{$curr} 1,000,000 - {$curr} 2,000,000",
            '2000000-5000000' => "{$curr} 2,000,000 - {$curr} 5,000,000",
            '5000000-10000000' => "{$curr} 5,000,000 - {$curr} 10,000,000",
            '10000000-20000000' => "{$curr} 10,000,000 - {$curr} 20,000,000",
            '20000000-50000000' => "{$curr} 20,000,000 - {$curr} 50,000,000",
            '50000000-999999999' => "{$curr} 50,000,000 +"
        ];

        // dd($properties);

        $view_data = array(
            'template' => $template,
            'footerTemplate' => $footerTemplate,
            'meta' => $meta,
            'criteria' => $criteria,
            'propertyTypes' => $propertyTypes,
            'properties' => $properties,
            'sort_list_data'=> $sort_property_list,
            'search_content' => $search_content,
            'posts' => $posts,
            'saved_search' => $saved_search,
            'community'=>$community,
            'page' => $page,
            'priceList'=>$priceList
           
            
        );



        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view,  $view_data);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.search', $view_data);
        }

    }



    // public function getDynamicSearchData(Request $request)
    // {
    //     try {
    //         $validator = Validator::make($request->all(), [
    //             'type' => 'required|in:location,area,complex',
    //             'parent' => 'nullable|string',
    //             'parentType' => 'nullable|in:property_type,location,area'
    //         ]);
    
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'error' => 'Invalid input',
    //                 'details' => $validator->errors()
    //             ], 400);
    //         }
    
    //         $type = $request->input('type');
    //         $parentValue = $request->input('parent', '');
    //         $parentType = $request->input('parentType', '');
    
    //         $query = Property::where('status', '>=', 0);
    
    //         // Apply parent filter based on parent type
    //         switch ($parentType) {
    //             case 'property_type':
    //                 $query->where('property_type', $parentValue);
    //                 break;
    //             case 'location':
    //                 $query->where('in', $parentValue);
    //                 break;
    //             case 'area':
    //                 $query->where('area', $parentValue);
    //                 break;
    //         }
    
    //         // Select and process data based on type
    //         switch ($type) {
    //             case 'location':
    //                 $items = $query->select('in')
    //                     ->distinct()
    //                     ->get()
    //                     ->filter(function ($value) {
    //                         return !empty($value->in);
    //                     })
    //                     ->map(function ($property) {
    //                         return [
    //                             'id' => urlencode($property->in),
    //                             'name' => $property->in,
    //                             'count' => Property::where('in', $property->in)->count()
    //                         ];
    //                     })
    //                     ->values();
    //                 break;
                
    //             case 'area':
    //                 $items = $query->select('area')
    //                     ->distinct()
    //                     ->get()
    //                     ->filter(function ($value) {
    //                         return !empty($value->area);
    //                     })
    //                     ->map(function ($property) {
    //                         return [
    //                             'id' => urlencode($property->area),
    //                             'name' => $property->area,
    //                             'count' => Property::where('area', $property->area)->count()
    //                         ];
    //                     })
    //                     ->values();
    //                 break;
                
    //             case 'complex':
    //                 $items = $query->select('complex')
    //                     ->distinct()
    //                     ->get()
    //                     ->filter(function ($value) {
    //                         return !empty($value->complex);
    //                     })
    //                     ->map(function ($property) {
    //                         return [
    //                             'id' => urlencode($property->complex),
    //                             'name' => $property->complex,
    //                             'count' => Property::where('complex', $property->complex)->count()
    //                         ];
    //                     })
    //                     ->values();
    //                 break;
                
    //             default:
    //                 return response()->json([
    //                     'error' => 'Invalid search type'
    //                 ], 400);
    //         }
    
    //         return response()->json($items->isEmpty() ? [] : $items);
    
    //     } catch (\Exception $e) {
    //         \Log::error('Dynamic Search Data Error: ' . $e->getMessage(), [
    //             'type' => $type,
    //             'parentValue' => $parentValue,
    //             'parentType' => $parentType,
    //             'trace' => $e->getTraceAsString()
    //         ]);
    
    //         return response()->json([
    //             'error' => 'Internal server error',
    //             'message' => 'Unable to retrieve search data'
    //         ], 500);
    //     }
    // }





    public function save_search(Request $request)
    {
        $search_data = $request->input('search_data');

        // Convert to Request Data....
        $search_data = new Request($search_data);

        // Now pass to index function for a return of the criteria...
        $criteria = $this->search($search_data, true);

        // Cleanup the Search Data to Save...
        $except = ['_method', '_token'];
        $cleanup_data = $search_data->except($except);

        // See if a Lead Already Exists...
        $lead_exists = LeadAutomation::where('lead_type', 'search')
            ->where('lead_is_subscribed', 'y')
            ->where('user_id', $this->user_id)
            ->first();

        if(!$lead_exists)
        {
            // Create a new lead Category = Search...
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
            $lead_automation->lead_type = "search";
            $lead_automation->lead_is_subscribed = 'y';
            $lead_automation->lead_contact_type = 'email';
            $lead_automation->last_contacted = Carbon::now();
            $lead_automation->user_id = $this->user_id;
            $lead_automation->save();
        }

        // Save The Search...
        $save_search = new SaveSearch;
        $save_search->user_id = Auth::user()->id;
        $save_search->saved_search_criteria = json_encode($cleanup_data);
        $save_search->saved_search_url = rtrim($criteria, '/');
        $save_search->save();

        // If Propertybase - Send The Search Detail to their API...
        if(settings('propertybase'))
        {
            // Fall Back If Logged In Only....
            if(Auth::user())
            {

                //$this->post_saved_search($save_search->saved_search_id, $cleanup_data, Auth::user()->id);

            }
        }

        // Return JSON...
        return response()->json(
            [
                'id'        => $save_search->saved_search_id,
                'message'   => 'Search Saved',
            ]
        );

    }

    public function delete_search(Request $request, $id)
    {
        $refer = $request->server('HTTP_REFERER');
        $page = explode("/", $refer);

        $saved_search = SaveSearch::find($id);

        if($saved_search)
        {
            $destroy = SaveSearch::destroy($id);

            // Return a page for response (Delete a Div or switch the text logic on a button)...
            // Return JSON...
            return response()->json(
                [
                    'title'     => 'Removed',
                    'remaining' => SaveSearch::where('user_id', Auth::user()->id)->count(),
                    'page'      => end($page)
                ]
            );
        }
    }

    /*
    * Property page
    *
    */
    public function property(Request $request, $propertyUrl, $propertyId)
    {
        $template = 1;

        $property = Property::where('status', '>=', 0)->where('status', '!=', 1)->find($propertyId);

        // If the property is Inactive or Archived, we need to show a kind of offline page,
        // and need to show similar properties

        if(!empty($property))
        {

            // Property page counter
            $propertyStats = new PropertyStats();
            $propertyStats->property_view_store($propertyId);

            // If Propertybase Enabled, Send View to Propertybase...
            if(settings('propertybase'))
            {
                if(Auth::user())
                {
                    // If User Role is a Member....
                    if(Auth::user()->role_id == '4')
                    {
                        // Logged In User (Send User ID)...
                        $user_id = Auth::user()->id;
                        $this->post_property_view($propertyStats->id, $property, $user_id);
                    }
                }
                else
                {
                    if ($request->session()->exists('user_id'))
                    {
                        // Send UUID Through to follow property views based on user enquiring...
                        $user_id = $request->session()->get('user_id');
                        $this->post_property_view($propertyStats->id, $property, $user_id);
                    }
                    else
                    {
                        // Anonymous User - Still send...
                        $this->post_property_view($propertyStats->id, $property);
                    }
                }

            }

            if ($request->session()->exists('latest_search')) {
                $back_url = $request->session()->get('latest_search');
                if(strpos($back_url, 'drawmap') !== false ){
                    $mode = ($property->is_rental==1)? 'rent' : 'sale';
                    $back_url = $back_url.'?mode='.$mode;
                }

            }else{
                $back_url = url('property-for-sale');
            }

            // News Article(s)...
            $news = Post::whereHas('location_tags', function( $query ) use ($property)
            {
                $query->where('post_tag_value', $property->city );
                $query->orWhere('post_tag_value', $property->town );
            })->get();

            // Metadata
            $meta = get_metadata($property, 'property');

            // ** New - Development ** //
            if($property->is_development == 'y' && settings('new_developments'))
            {
                $view = 'frontend.demo1.page-templates.development';

                if(view()->exists($view))
                {
                    return view($view, [
                        'template' => $template,
                        'meta' => $meta,
                        'property' => $property,
                        'back_url' => $back_url,
                        'posts'  => $news,
                        'page' => 'property'
                    ]);
                }
                else
                {
                    // Fallback for Development(s) not being available in D1, 2 & 3..
                    $view = 'frontend.demo1.property';
                }
            }

            // If Property Template is different, use specific bespoke template for that page...
            $view = 'frontend.demo1.property';

            if(view()->exists($view))
            {
                return view($view, [ // Shared View in the Templates...
                    'template' => $template,
                    'meta' => $meta,
                    'property' => $property,
                    'back_url' => $back_url,
                    'posts'  => $news,
                    'page' => 'property'
                ]);
            }
            else
            {
                // Load Shared View...
                return view('frontend.shared.property', [ // Shared View in the Templates...
                    'template' => $template,
                    'meta' => $meta,
                    'property' => $property,
                    'back_url' => $back_url,
                    'posts'  => $news,
                    'page' => 'property'
                ]);
            }

        }else{
          // LOAD OFFLINE PAGE
          $route = 'offline';
          $page = Page::where('route', $route)->firstOrFail();
          $meta = get_metadata($page);
          return view('frontend.demo1.page', [ 'route' => $route, 'meta' => $meta, 'page' => $page ]);
        }

    }

    public function propertyPrimaryPhoto($propertyId)
    {
    
        $property = Property::where('status', 0)->find($propertyId);
        $contentType = 'image/jpeg';
        $is_default = false;
    
        if (!empty($property)) {
            $image = $property->PrimaryPhoto;
        } else {
            $image = default_thumbnail();
            $is_default = true;
        }
    
        if ($image == default_thumbnail()) {
            $is_default = true;
        }
    
        if (!empty($property->PrimaryPhotoExt)) {
    
            switch ($property->PrimaryPhotoExt) {
                case 'gif':
                    $contentType = 'image/gif';
                    break;
                case 'png':
                    $contentType = 'image/png';
                    break;
                case 'tiff':
                    $contentType = 'image/tiff';
                    break;
                case 'webp':
                    $contentType = 'image/webp';
                    break;
            }
        }
    
    header('content-type: '.$contentType);
    readfile($image);
    
    }

    /*
    * PDF DISPLAY
    */
    public function property_pdf(Request $request, $propertyUrl, $propertyId){
        if(settings('pdf_view') == 1){
            $property = Property::findOrFail($propertyId);

            $url = url('');
            $asset = themeAsset();

            $data['property'] = $property;
            $data['url'] = $url;
            $data['asset'] = $asset;

            $html = view('frontend.shared.pdf.property-pdf')
                   ->with($data)->render();

            $filename = $propertyUrl.'-REF-'.$property->ref.'.pdf';

            return Pdf::setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true])
                ->loadHTML($html)
                ->setWarnings(false)
                ->setPaper('A4', 'portrait')
                ->stream($filename);
        }else{
            return redirect(url('property/'.$propertyUrl.'/'.$propertyId));
        }

    }

    public function generate_pdf(Request $request, $propertyId){
        $property = Property::findOrFail($propertyId);

        $url = url('');
        $asset = themeAsset();

        $data['property'] = $property;
        $data['url'] = $url;
        $data['asset'] = $asset;

        $html = view('frontend.shared.pdf.property-pdf')
            ->with($data)
            ->render();

        return $html;

        $pdf = Pdf::setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true
            ])
            ->loadHTML($html)
            ->setWarnings(false)
            ->setPaper('A4', 'portrait');

        $path = 'properties/' . $property->id . '/propertybrochure.pdf';

        Storage::disk('public')->put($path, $pdf->output());

        // store original updated_at value
        $originalUpdatedAt = $property->updated_at;

        // disable timestamp update
        $property->timestamps = false;

        DB::table('properties')
        ->where('id', $property->id)
        ->update([
            'property_pdf_path' => $path,
            'pdf_created_at'   => $property->updated_at,
        ]);
        
        return $pdf->stream('propertybrochure.pdf');
    }


    /*
    * Get the location list from properties
    * requested by autocomplete through ajax
    */
    public function get_locationList(Request $request)
    {
        //init
        $q =(!empty($request->q)) ? $request->q : "";
        $items = [];
        $properties = [];
        $temp_locations = [];
        $ctr = 0;

        //FIELD TO SEARCH
        $locations = ['name', 'ref', 'street', 'town', 'city', 'region', 'complex_name', 'postcode'];

        foreach( $locations as $location){ //FIELD TO SEARCH
            $properties = Property::where([['status', 0],[$location, 'LIKE', $q.'%']])->get();
            if(!empty($properties)){

                foreach( $properties as $property){ //LOOP PROPERTIES HAVING THE SAME Q
                    if( !in_array($property->{$location}, $temp_locations) ){
                        $new_item = new \stdClass();
                        //$new_item->id = time().'-'.mt_rand();
                        $new_item->id = urlencode($property->{$location});
                        $new_item->name = $property->{$location};
                        $items[] = $new_item;
                        $temp_locations[] = $property->{$location};
                    }
                }
            }
        }

        // ARRAY PAGINATION
        $page = ! empty( $request->page ) ? (int) $request->page : 1;
        $total = count( $items ); //total items in array
        $limit = 10; //per page
        $totalPages = ceil( $total/ $limit ); //calculate total pages
        $page = min($page, $totalPages); //get last page when $request->page > $totalPages
        $offset = ($page - 1) * $limit;
        if( $offset < 0 ) $offset = 0;
        $itemsSliced = array_slice( $items, $offset, $limit );
        $morePages = $page + 1;
        // END OF ARRAY PAGINATION

        //JSON
        $json["q"] = $q;
        $json["pagination"] = array( "more" => $morePages );
        $json["total_count"] = count($items);
        $json["page"] = $page;
        $json["items"] = $itemsSliced;
        echo json_encode( $json );
    }
/*
    * Get the country list from properties
    * requested by autocomplete through ajax
    */
    public function get_countryList(Request $request)
    {
        //init
        $q =(!empty($request->q)) ? $request->q : "";
        $items = [];
        $properties = [];
        $temp_locations = [];
        $ctr = 0;

        //FIELD TO SEARCH
        $locations = ['country'];

        foreach( $locations as $location){ //FIELD TO SEARCH
            $properties = Property::where([['status', 0],[$location, 'LIKE', $q.'%']])->get();
            if(!empty($properties)){

                foreach( $properties as $property){ //LOOP PROPERTIES HAVING THE SAME Q
                    if( !in_array($property->{$location}, $temp_locations) ){
                        $new_item = new \stdClass();
                        //$new_item->id = time().'-'.mt_rand();
                        $new_item->id = urlencode($property->{$location});
                        $new_item->name = $property->{$location};
                        $items[] = $new_item;
                        $temp_locations[] = $property->{$location};
                    }
                }
            }
        }

        // ARRAY PAGINATION
        $page = ! empty( $request->page ) ? (int) $request->page : 1;
        $total = count( $items ); //total items in array
        $limit = 10; //per page
        $totalPages = ceil( $total/ $limit ); //calculate total pages
        $page = min($page, $totalPages); //get last page when $request->page > $totalPages
        $offset = ($page - 1) * $limit;
        if( $offset < 0 ) $offset = 0;
        $itemsSliced = array_slice( $items, $offset, $limit );
        $morePages = $page + 1;
        // END OF ARRAY PAGINATION

        //JSON
        $json["q"] = $q;
        $json["pagination"] = array( "more" => $morePages );
        $json["total_count"] = count($items);
        $json["page"] = $page;
        $json["items"] = $itemsSliced;
        echo json_encode( $json );
    }

    /*
    * Get the country list from properties
    * requested by autocomplete through ajax
    */

    

    public function get_areaList(Request $request)
    {
        //init
        $country =(!empty($request->country)) ? $request->country : "";
        $items = [];
        $properties = [];
        $temp_locations = [];
        $ctr = 0;

        //FIELD TO SEARCH
        $locations = ['town'];

        foreach( $locations as $location){ //FIELD TO SEARCH
            $properties = Property::select('town')->where([['status','>=', 0],['country', $country],['town','!=','null']])->get();
            if(!empty($properties)){

                foreach( $properties as $property){ //LOOP PROPERTIES HAVING THE SAME Q
                    if( !in_array($property->{$location}, $temp_locations) ){
                        $new_item = new \stdClass();
                        //$new_item->id = time().'-'.mt_rand();
                        $new_item->id = urlencode($property->{$location});
                        $new_item->name = $property->{$location};
                        $items[] = $new_item;
                        $temp_locations[] = $property->{$location};
                    }
                }
            }
        }

        //JSON
       
        echo json_encode( $items );
    }

    public function get_complexList(Request $request)
    {
        //init
        $country =(!empty($request->country)) ? $request->country : "";
        $items = [];
        $properties = [];
        $temp_locations = [];
        $ctr = 0;

        //FIELD TO SEARCH
        $locations = ['complex_name'];

        foreach( $locations as $location){ //FIELD TO SEARCH
            $properties = Property::select('complex_name')->where([['status','>=', 0],['country', $country],['complex_name','!=','null']])->get();
            if(!empty($properties)){

                foreach( $properties as $property){ //LOOP PROPERTIES HAVING THE SAME Q
                    if( !in_array($property->{$location}, $temp_locations) ){
                        $new_item = new \stdClass();
                        //$new_item->id = time().'-'.mt_rand();
                        $new_item->id = urlencode($property->{$location});
                        $new_item->name = $property->{$location};
                        $items[] = $new_item;
                        $temp_locations[] = $property->{$location};
                    }
                }
            }
        }

        //JSON

        echo json_encode( $items );
    }

    public function get_prices_type($type)
    {
        if($type == 'sale')
        {
            $values = sale_price();
        }
        else
        {
            $values = rent_price();
        }

        return json_encode($values);

    }


    /*
    * Calculate mortgage
    */
    public function calculate_mortgage(Request $request)
    {
        $rules = array
            (
                'loan'       => 'required',
                'contribution'      => 'required',
                'year'          => 'required',
                'rate'       => 'required'
            );

        $validator = Validator::make($request->all(), $rules);

        // set nice names
        $validator->setAttributeNames(
            [
                'loan'       => 'MORTGAGE AMOUNT',
                'contribution'      => 'CONTRIBUTION',
                'year'          => 'MORTGAGE PERIOD',
                'rate'       => 'INTEREST RATE'
            ]
        );


        if ($validator->fails()){

            // get the feilds with error
            $invalidFeilds = invalidFeilds($rules, $validator);
            $errors = $validator->errors();
            $error_txt = false;
            foreach ($errors->all() as $message)
            {
                $error_txt .= "$message ";
            }

          $json["alert"] = '<div class="alert alert-danger alert-dismissible show c-dark" role="alert">
                          <strong>Error!</strong> '.$error_txt.'
                          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>';

          $json["flag"] = 0;
          $json["output"] = "";
          $json["owner"] = "";

        } else {
          $post = $request->input(NULL, TRUE);

          $owner = (int)$post['contribution'];
          $l = (int)$post['loan'] - $owner;
          //$p = $post['price'] * ($l/100);
          //$owner = 100 - $l;
          $np = $l; //  new price
          $i =  (float)$post['rate'];
          $t =  (int)$post['year'];
          $m = 12; // Number of Payments per Year
          $tp = $t * $m; // Total Number of Payments

          $PMT = pmt($i, $t, $np);

          $output = $PMT;
          $sp = $PMT * $tp; // Sum of Payments

          $json["alert"] = "";
          $json["flag"] = 1;
          $json["output"] = '£'.number_format($output, 2);

        }
        echo json_encode($json);
    }

/* Stamp Duty Calculator
    ------------------------------------------------*/
    public function stamp_duty_calculator(Request $request)
    {
        //Variables
        $calc_type = $request->input('calc_type');
        $price = $request->input('price');
        $price = str_replace(',', '', $price);
        $price = (int)$price;

        //Prepare a list of formulas
        $criterias['single'] = [
            '0|0-300000|Up to £300k',
            '5|300000-500000|£300k - £500k',
            '8|500000-9250000|£500k - £925k',
            '10|9250000-9999999999|£925 - £1.5m',
            '12|9999999999-99999999999|Over £1.5m'
        ];
        $criterias['additional'] = [
              '3|0-300000|Up to £300k',
            '5|300000-500000|£300k - £500k',
            '8|500000-9250000|£500k - £925k',
            '13|9250000-9999999999|£925 - £1.5m',
            '15|9999999999-99999999999|Over £1.5m'
        ];

        $criteria = $criterias[$calc_type];

        $tax_sum_data = $this->get_taxable_sum($criteria, $price);

        // generate table rows
        $rows = false;
        $total = 0;
        $i = 0;
        foreach($tax_sum_data as $tax_sum_percent => $tax_sum_value){

            // get the tax and subtotal
            $percent = str_replace('percent-', '', $tax_sum_percent);
            $tax = $tax_sum_value * ($percent / 100);
            $total += $tax;
            $tax_display = number_format($tax);
            $tax_sum_value_display = number_format($tax_sum_value);

            // get the label
            $criteria_data = explode('|', $criteria[$i]);
            $label = $criteria_data[2];

            // highlight if tax not empty
            $class = $tax ? 'c-green-i' : false;

            $rows .= "<tr>
                <td>{$label}</td>
                <td>{$percent}</td>
                <td>".settings('currency_symbol')."{$tax_sum_value_display}</td>
                <td><span class='{$class}'>".settings('currency_symbol')."{$tax_display}</span></td>
            </tr>";
            $i++;
        }

        // get the effective rate
        $effective_rate = number_format(($total / $price) * 100, 2);

        $json = [
            'alert' => '',
            'flag' => 1,
            'amount' => settings('currency_symbol').number_format($price),
            'total' => $total,
            'total_display' => settings('currency_symbol').number_format($total),
            'rows' => $rows,
            'effective_rate' => $effective_rate.'%',
        ];
        echo json_encode($json);
    }

 public function set_currency(Request $request, $currency='AED')
    {
        if($currency=='default'){
            Session::forget('current_currency');
        }else{
            Session::put('current_currency', $currency);
        }
        return back();
    }
    /* MAP SEARCH PROPERTIES
    ------------------------------------------------*/
    public function ajax_search_map(Request $request)
    {
        $ajax_property_ids = $request->input('ajax_property_ids');
        $ajax_property_ids_array = explode('|', $ajax_property_ids);
        $propertyMarker = [];

        $properties_group = $this->get_similar_latlng($ajax_property_ids_array);

        if(count($properties_group))
        {
            foreach($properties_group as $properties)
            {
                if(count($properties))
                {
                    //variables...
                    $propertyCount = count($properties);
                    $position = [
                        "lat"=>!empty($properties[0]->latitude)?$properties[0]->latitude:'',
                        "lng"=>!empty($properties[0]->longitude)?$properties[0]->longitude:''
                    ];
                    $property_content = $this->format_infowindow_property($properties);

                    //marker data...
                    $propertyMarker[] = [
                        "icon"=>"search-marker-1.png",
                        "counter"=>(string) $propertyCount.' listings',
                        "content"=>(string) $property_content,
                        "position" => $position
                    ];

                }
            }
        }
        $data["pins"] = $propertyMarker;
        echo json_encode($data);
    }

    public function propertysearchprice(Request $request, $propertyUrl, $propertyId, $currency)
    {
        $all_currencies = ['AED', 'GBP', 'USD', 'EUR', 'CZK'];

        if (!empty($currency) && in_array($currency, $all_currencies)) {
            session(['current_currency' => $currency]);
        }

        return redirect("/property/{$propertyUrl}/{$propertyId}");
    }
}
