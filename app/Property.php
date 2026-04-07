<?php

namespace App;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Enquiry;
use App\Shortlist;
use App\Models\Community;
use App\Models\PropertyUpdateHistory;
use App\Models\PropertyClaim;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TranslateTrait;
use App\Traits\CountryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Property extends Model
{
    public function claimedByUsers()
    {
        return $this->belongsToMany(User::class, 'property_claims')->withPivot('property_status','property_value','property_provide_date','property_claim_approved');
    }

    public function claimdPData(){
        return $this->hasOne(PropertyClaim::class);
    }

    public function updateHistories()
    {
        return $this->hasMany(
            PropertyUpdateHistory::class,
            'property_id'
        );
    }
    
    use CountryTrait;
    use SoftDeletes;
    use TranslateTrait;

    protected $dates = ['archived_at'];
    const DELETED_AT = 'archived_at';

    protected $table = 'properties';

    // Primary key
    public $primaryKey = 'id';

    // Timestamps
    public $timestamps = true;

    protected $admin_allowed = ['for',
        'in',
        'area',
        'complex',
        'ref',
        'is_featured',
        'minbeds',
        'category',
        'community',
        'property_type_id',
        'property-type-ids',
        'category-ids',
        'is-brown-harris-stevens',
        'property-type',
        'price_range',
        'min-price',
        'max-price',
        'status',
        'admin_approval',
        'is_admin_approval',
        'sevenDays',
        'oneDay',
        'popular',
        'beds', // Added this one for the front-end...
        'baths',
        'sort', // And this one...
        'exclude_id', // And this one... (Similar Properties)
        'exclude_ids', // Array of ID's to exclude
        'branch_id', // Branch ID for Property Branches
        'ne-lat', //maps
        'ne-lng', //maps
        'sw-lat', //maps
        'sw-lng', //maps
        'max', // maps
        'e_status', //enquiries status search
        'agent_notes', //agent notes
        'is_development',
        'updated_at'
    ];

    // Columns to Ignore on a Propertybase Install - This populates the select dropdowns...
    protected $ignore_columns = [
        'id',
        'property_type_id',
        'user_id',
        'feed_id',
        'is_rental',
        'is_featured',
        'rent_period',
        'internal_area_unit',
        'land_area_unit',
        'balcony_area_unit',
        'development_id',
        'client_valuation_id',
        // 'is_development',
        'agent_notes',
        'archived_at',
        'created_at'
    ];

    protected $casts =
    [
        'propertybase_fields' => 'object'
    ];

    //protected $fillable = ['is_rental'];
 

    public function getTableColumns()
    {
        $table_cols = $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
        $columns = new Collection($table_cols);

        foreach($columns as $key => $value)
        {
            if(in_array($value, $this->ignore_columns))
            {
                unset($columns[$key]);
            }
        }

        // Make Friendly Object (k = column, v = Friendly Name i.e Baths)
        $friendly = array();
        foreach($columns as $column)
        {
            $friendly[$column] = ucwords(str_replace('_', ' ', $column));
        }

        return $friendly;
    }

    /**
     * Get the user record associated with the property media
     *
     */
    public function propertyMedia()
    {
        return $this->hasMany('App\PropertyMedia')->orderBy('sequence', 'ASC');
    }

    /**
     * Get the user record associated with the property media photo
     *
     */
    public function propertyMediaPhotos()
    {
        return $this->hasMany('App\PropertyMedia')->where('type', 'photo')->orderBy('sequence', 'ASC');
    }

    public function propertySearchMediaPhotos()
   {
       return $this->hasMany('App\PropertyMedia')->where('type', 'photo')->orderBy('sequence', 'ASC')->limit(2);
   }


    /**
     * Get the user record associated with the property media floorplan
     *
     */
    public function propertyMediaFloorplans()
    {
        return $this->hasMany('App\PropertyMedia')->where('type', 'floorplan')->orderBy('sequence', 'ASC');
    }

    /**
     * Get the user record associated with the property media documents
     *
     */
    public function propertyMediaDocuments()
    {
        return $this->hasMany('App\PropertyMedia')->where('type', 'document')->orderBy('sequence', 'ASC');
    }

    /**
     * Get the user record associated with the property
     *
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the user record associated with the property
     *
     */
    public function users()
    {
        //Users::all();
        //return $this->hasMany('App\User');
        require $this->all('App\User');
    }

    /**
     * Get the property type record associated with the property
     *
     */
    public function type()
    {
        return $this->belongsTo('App\PropertyType', 'property_type_id', 'id')->orderBy('name', 'asc');
    }

    /**
     * Get the Shortlist record associated with the property
     *
     */
    public function shortlists()
    {
        return $this->belongsTo('App\Shortlist', 'id', 'property_id');
    }

    public function communities()
    {
        return $this->hasManyThrough('App\Models\Community', 'App\Models\PropertyCommunity',
            'property_id',
            'id',
            'id',
            'community_id');
    }

    public function categories()
    {
        return $this->hasManyThrough('App\Models\Category', 'App\Models\PropertyCategory',
            'property_id',
            'id',
            'id',
            'category_id');
    }

    // filter by IP or User ID
    public function shortlist_ip()
    {
        $ip = request()->ip();
        if(Auth::user())
        {
            $user_id = Auth::user()->id;
        }
        else
        {
            $user_id = null;
        }

        if(settings('members_area')){
            return $this->belongsTo('App\Shortlist', 'id', 'property_id')
                ->where('property_id', $this->id)
                ->where('user_id', $user_id);
        }else{
            return $this->belongsTo('App\Shortlist', 'id', 'property_id')
                ->where('property_id', $this->id)
                ->where('ip', $ip);
        }

    }

    // Check if shortlist exist by IP or User
    public function getCheckShortlistIpAttribute()
    {
        $check = FALSE;

        $ip = request()->ip();

        if(Auth::user())
        {
            $user_id = Auth::user()->id;
        }
        else
        {
            $user_id = null;
        }

        if(settings('members_area'))
        {
            $checked = $this->shortlists()
                ->where('property_id', $this->id)
                ->where('user_id', $user_id);
        }
        else
        {
            $checked = $this->shortlists()
                ->where('property_id', $this->id)
                ->where('ip', $ip);
        }

        if($checked->count() > 0 )
        {
            $check = TRUE;
        }

        return $check;
    }

    /**
     * Get the property enquiries record associated with the property
     *
     */
    public function propertyEnquiries()
    {
        //return $this->hasMany('App\Enquiry', 'ref', 'ref')->where('archived_at', NULL)->orderBy('created_at', 'DESC');
        return $this->hasMany('App\Enquiry', 'ref', 'ref')->orderBy('created_at', 'DESC');
    }

    public function enquiries()
    {
        return $this->belongsTo('App\Enquiry', 'ref', 'ref');
    }

    public function branch()
    {
        return $this->hasOne('App\Models\Branch', 'branch_id', 'branch_id');
    }

    /**
     * Fetch properties for the Properties Sitemap XML
     */
    public static function getAllForSitemap()
    {
        $properties = Property::where([
            ['status', 0]
        ])
        ->whereNull('archived_at')
        ->paginate(350);

        return $properties;
    }

    /**
     * Fetch properties for display in the Admin's Properties page
     *
     */
    public static function getProperties($paginate = 10)
    {
        $properties = Property::latest()->paginate($paginate);

        return $properties;
    }

    // query scope to order by the latest properties
    public function scopeLatest($query)
    {
        return $query->order_by('created_at', 'desc');
    }

    // get property type or empty
    /*
    public function getPropertyTypeNameAttribute()
    {
        $typeName = '';
        if(!empty($this->property_type_id)){
             $typeName = (!empty($this->type->name)) ? $this->type->name : '';
        }
        return $typeName;
    }
    // get property type or property
    public function getPropertyTypeNamePlaceholderAttribute()
    {
        $typeName = '';
        if(!empty($this->property_type_id)){
             $typeName = (!empty($this->type->name)) ? $this->type->name : 'Property';
        }
        return $typeName;
    }
    */

    public function getPropertyTypeNameAllAttribute()
    {
        $typeName = '';
        $typeNameArray = [];
        if(!empty($this->property_type_ids)){
            $get_ptypes = get_ptype();
            $property_type_ids_array = explode(',', $this->property_type_ids);
            $ctr=0;
            if(is_array($property_type_ids_array) && count($property_type_ids_array)){
                foreach($property_type_ids_array as $propertyTypeID){
                    if (array_key_exists($propertyTypeID,$get_ptypes)){
                        $typeNameArray[] = !empty($get_ptypes[$propertyTypeID])?$get_ptypes[$propertyTypeID]:'';
                    }
                }
            }
            $typeName = implode(', ',$typeNameArray);
        }
        return $typeName;
    }

    public function getPropertyTypeNameAttribute()
    {
        $typeName = '';
        $typeNameArray = [];
        if(!empty($this->property_type_id)){
            $get_ptypes = get_ptype();
            $property_type_ids_array = explode(',', $this->property_type_id);
            $ctr=0;
            if(is_array($property_type_ids_array) && count($property_type_ids_array)){
                foreach($property_type_ids_array as $propertyTypeID){
                    if (array_key_exists($propertyTypeID,$get_ptypes)){
                        if($ctr < 3){
                            $typeNameArray[] = !empty($get_ptypes[$propertyTypeID])?$get_ptypes[$propertyTypeID]:'';
                        }
                    }
                    $ctr++;
                }
            }
            $typeName = implode(', ',$typeNameArray);
        }
        return $typeName;
    }

    // get property type or property
    public function getPropertyTypeNamePlaceholderAttribute()
    {
        $typeName = $this->getPropertyTypeNameAttribute();
        $typeName = (!empty($typeName)) ? $typeName : 'Property';
        return $typeName;
    }

    // get default area unit
    public function getAreaUnitAttribute()
    {
        $area_unit = settings('area_unit');
        return !empty($area_unit) ? $area_unit : 'sqm';
    }

    public function getLandAreaUnitAttribute()
    {
        $area_unit = settings('area_unit_land');
        return !empty($area_unit) ? $area_unit : 'sqm';
    }

    // get default area unit
    public function getDisplayLandUnitAttribute()
    {
        return (!empty($this->land_area_unit)) ? $this->land_area_unit : $this->getLandAreaUnitAttribute();
    }

    public function getDisplayLandAttribute()
    {
        return $this->land_area.' '.$this->getDisplayLandUnitAttribute();
    }

    // get default area unit
    public function getDisplayInternalUnitAttribute()
    {
        return (!empty($this->internal_area_unit)) ? $this->internal_area_unit : $this->getAreaUnitAttribute();
    }

    public function getDisplayInternalAttribute()
    {
        return $this->internal_area.' '.$this->getDisplayInternalUnitAttribute();
    }

    /**
     * Accessor:
     * The full address of the property, likely used in the Admin
     *
     */
    public function getDisplayInternalAreaAttribute()
    {
        //$value = $this->internal_area.'m<sup>2</sup>';
        $value = $this->internal_area.' '.$this->DisplayInternalUnit;

        return $value;
    }


    /**
     * Accessor:
     * The headline for search results, Similar Properties, Featured Properties etc
     *
     */
    public function getSearchHeadlineAttribute()
    {

        $typeName = $this->PropertyTypeName;

        $value = sprintf('%s%s',
            !empty($this->beds) ? $this->beds.' Bed ' : '', $typeName
        );

        $value = !empty($this->name) ? $this->name : $value;

        return $value;
    }

    /**
     * Accessor:
     * The full address of the property, likely used in the Admin Template 2
     *
     */
    public function getSearchHeadlineV2Attribute()
    {

        if(settings('overseas') == 1){
            $address = '<span>'.implode(', ', array_filter([$this->complex_name, $this->town, $this->city])).'<span>';
        }else{
            $value = implode(', ', array_filter([$this->town, $this->city, $this->region]));
        }

        $value = sprintf('%s%s',
            !empty($this->beds) ? $this->beds.' Bed ' : '',
            $this->PropertyTypeName
        );

        if( !empty($this->name) ){
            $value = $this->name;
        }

        $value = $value.', '.$address;

        return $value;
    }

    /**
     * Accessor:
     * The headline for Property details pages
     *
     */
    public function getDetailsHeadlineAttribute()
    {
        // format: [NUMBER OF BEDS] [PROPERTY TYPE] [FOR SALE / TO RENT] in [REGION]

        $items = [];
        if ($this->beds) $items[] = $this->beds.' Bed';
        $items[] = $this->PropertyTypeName;
        $items[] = ($this->is_rental) ? 'To Rent' : 'For Sale';
        if ($this->region) $items[] = 'in '.$this->region;
        $value = implode(' ', $items);

        $value = !empty($this->name) ? $this->name : $value;

        return $value;
    }

    /**
     * Accessor:
     * The headline for Property details v2 pages
     *
     */
    public function getDetailsHeadlineV2Attribute()
    {
        // format: [NUMBER OF BEDS] [PROPERTY TYPE] [FOR SALE / TO RENT] in [REGION]

        $items = [];
        if ($this->beds) $items[] = $this->beds.' Bed';
        $items[] = $this->PropertyTypeName;
        $items[] = ($this->is_rental) ? 'To Rent' : 'For Sale';
        $value = implode(' ', $items);

        $value = !empty($this->name) ? $this->name : $value;

        return $value;
    }

    /**
     * Accessor:
     * The full address of the property, likely used in the Admin
     *
     */
    public function getDisplayAddressAttribute()
    {
        if(settings('overseas') == 1){
            $value = implode(', ', array_filter([$this->complex_name, $this->street, $this->town, $this->city, $this->country]));
        }else{
            $value = implode(', ', array_filter([$this->street, $this->town, $this->city, $this->region, $this->country]));
        }

        return $value;
    }

    //Featured and Similar Properties line one
    public function getFsAddress1Attribute()
    {
        //Town/Village - dark
        if(settings('overseas') == 1){
            $value = implode(', ', array_filter([$this->complex_name, $this->town]));
        }else{
            $value = $this->town;
        }
        return $value;
    }

    //Featured and Similar Properties line two
    public function getFsAddress2Attribute()
    {
        if(settings('overseas') == 1){
            $value = $this->city;
        }else{
            $value = implode(', ', array_filter([$this->city, $this->region]));
        }
        return $value;
    }

    //Display generic address...
    public function getDisplayPropertyAddressAttribute()
    {
        if(settings('overseas') == 1)
        {
            // [$this->complex_name, $this->town, $this->city, $this->region]
            $value = implode(', ', array_filter([$this->country,$this->town,$this->complex_name]));
        }else{
            // [$this->town, $this->city, $this->region]
            $value = implode(', ', array_filter([$this->country,$this->town,$this->complex_name]));
        }

        return $value;
    }

    //Generic page latest properties
    public function getGenericPropertyAddressAttribute()
    {
        if(settings('overseas') == 1){
            $value = implode(', ', array_filter([$this->complex_name, $this->town, $this->city]));
        }else{
            $value = implode(', ', array_filter([$this->town, $this->city]));
        }
        return $value;
    }


    /**
     * Accessor:
     * Formatted date of the property creation date, used in the Admin
     *
     */
    public function getDisplayDateAttribute()
    {
        $value = admin_date($this->created_at);

        return $value;
    }

    /**
     * Accessor:
     * Display summary
     *
     */
    public function getSummaryAttribute()
    {
        $value = str_replace('&nbsp;','',$this->description);
        $value = Str::limit(strip_tags($value), 250, '...');
        return $value;
    }

    /**
     * Accessor:
     * Display excerpt
     *
     */
    public function getExcerptAttribute()
    {
        $value = str_replace('&nbsp;','',$this->description);
        $value = Str::limit(strip_tags($value), 150, '...');
        return $value;
    }


    /**
     * Accessor:
     * Display summary
     *
     */
    public function getSummaryDemo3Attribute()
    {
        $value = str_replace('&nbsp;','',$this->description);
        $value = Str::limit(strip_tags($value), 350, '...');
        return $value;
    }

    public function getSummaryPDFAttribute()
    {
        $value = str_replace('&nbsp;','',$this->description);
        $value = Str::limit(strip_tags($this->description), 800, '...');
        return $value;
    }

    public function getCommunityAttribute()
    {
        $p_communities = p_communities();
        $community = !empty($p_communities[$this->community_id]) && !empty($this->community_id) ?$p_communities[$this->community_id]:'';
        return $community;
    }

    public function getSubtypeAttribute()
    {
        $p_category = p_category();
        $category_type = (!empty($p_category[$this->category_type])&&!empty($this->category_type))?$p_category[$this->category_type]:'';
        return $category_type;
    }
      /**
     * Get the array of the different prices
     */
    public function getConvertedPricesAttribute()
    {
        
        if ($this->price_qualifier == 'POA') {            
            return 'Price on Application';
        }

        $qualifier = $this->price_qualifier;
         if ($this->price_qualifier == 'Default') {            
            $qualifier = '';
        }

        $rent_freq = '';
        if (!empty($this->is_rental)) {
            switch ($this->rent_period) {
                case 1:
                    $rent_freq = '/ day';
                    break;
                case 2:
                    $rent_freq = '/ wk.';
                    break;
                case 3:
                    $rent_freq = '/ mth.';
                    break;
                default:
                    $rent_freq = '/ p.a.';
                    break;
            }
        }

        $prices = [];
        $propertyCurrencies = all_currencies();
        $conversion = json_decode(file_get_contents(storage_path('app/public/currency-rates.json')));

        foreach ($propertyCurrencies as $currency => $symbol) {
            if ($currency == $conversion->base) {
                $price = $this->price;
                $price_max = $this->price_max;
            } else {
                $price = $this->price * $conversion->rates->{$currency};
                $price_max = $this->price_max * $conversion->rates->{$currency};
            }
            $symbol = $symbol.' ';
            $priceFormatted = (settings('price_format') == '1') ? $symbol . pw_price_format($price, '.') : $symbol . number_format($price);
            $priceFormatted_max = (settings('price_format') == '1') ? $symbol . pw_price_format($price_max, '.') : $symbol . number_format($price_max);

            $priceFormatted = (empty($this->price)) ? 'POA' : $priceFormatted;

            if ($priceFormatted != 'POA') {
                if (!empty($this->price_max)) {
                    $priceFormatted = $priceFormatted . '-' . $priceFormatted_max;
                }
            }

            $prices[$currency] = $qualifier.' '.$priceFormatted.' '.$rent_freq;
        }

        return $prices;
    }
    
    public function getDisplayPriceConvertedAttribute()
    {
        $preferences = false;

        if(Auth::check()) {
            $preferences = json_decode(Auth::user()->preferences);
        }

        $preference_currency = $preferences->currency ?? session('site_currency');
        $currency_opts = $this->ConvertedPrices ?? [];

        $the_price = $currency_opts[$preference_currency] ?? ' '.$this->display_price;

        return $the_price;
    }

    /**
     * Accessor:
     * Formatted display price of the property, including currency
     *
     */
    public function getDisplayPriceAttribute()
    {
        if ($this->price_qualifier == 'POA') {            
            return 'Price on Application';
        }

        $qualifier = $this->price_qualifier;
        
        if ($this->price_qualifier == 'Default') {            
            $qualifier = '';
        }

        $rent_freq = '';
        
        if (!empty($this->is_rental)) {
            switch ($this->rent_period) {
                case 1:
                    $rent_freq = ' / <abbr title="Daily">day<abbr>';
                    break;
                case 2:
                    $rent_freq = ' / <abbr title="Weekly">wk.<abbr>';
                    break;
                case 3:
                    $rent_freq = ' / <abbr title="Monthly">mth.<abbr>';
                    break;
                default:
                $rent_freq = '/p.a.';
                    break;
            }
        }

        $currency_symbol = settings('currency_symbol');
        $currency_symbol = (!empty($currency_symbol)) ? $currency_symbol : '&pound;';

        if(settings('price_format')=='1'){

          
            if (! empty($this->price_min) || ! empty($this->price_max))
            {
                // if price min and price max, use both instead of price
                if ($this->price_min && $this->price_max)
                {
                    $priceFormatted = pw_price_format($this->price_min,'.');
                    $priceFormatted .= '&ndash;'.pw_price_format($this->price_max,'.');
                }
                // if price max but no price min, check price is lower and use that
                elseif ($this->price && $this->price_max && $this->price < $this->price_max)
                {
                    $priceFormatted = pw_price_format($this->price,'.');
                    $priceFormatted .= '&ndash;'.pw_price_format($this->price_max,'.');
                }
                else
                // just use price
                {
                    $priceFormatted = pw_price_format($this->price,'.');
                }
            }
            else
                $priceFormatted = pw_price_format($this->price,'.');

        }else{

            // If sale, look for price min/price max - RH 4/5/2021
            if (! empty($this->price_min) || ! empty($this->price_max))
            {
                // if price min and price max, use both instead of price
                if ($this->price_min && $this->price_max)
                {
                    $priceFormatted = number_format($this->price_min);
                    $priceFormatted .= '&ndash;'.number_format($this->price_max);
                }
                // if price max but no price min, check price is lower and use that
                elseif ($this->price && $this->price_max && $this->price < $this->price_max)
                {
                    $priceFormatted = number_format($this->price);
                    $priceFormatted .= '&ndash;'.number_format($this->price_max);
                }
                else
                // just use price
                {
                    $priceFormatted = number_format($this->price);
                }
            }
            else
                $priceFormatted = number_format($this->price);
        }

         //Get all currencies available...
        $all_currencies = all_currencies();

        //Get current currency conversion...
        $current_currency = get_current_currency();

        $priceFormatted = $this->get_currency_conversions($this->price, isset($current_currency) ? $current_currency : 'AED');

        //Get currency symbol...
        if (!empty($all_currencies[$current_currency])) {
            $currency_symbol = $all_currencies[$current_currency];
        }

        $value = sprintf('%s%s%s%s',
            $qualifier.' ',
            $currency_symbol.' ',
            $priceFormatted,
            $rent_freq
        );

        return $value;
    }
    
    public function getDisplayPriceUSDAttribute()
    {
        if ($this->price_qualifier == 'POA') {            
            return 'Price on Application';
        }

         $qualifier = $this->price_qualifier;
         if ($this->price_qualifier == 'Default') {            
            $qualifier = '';
        }

        $rent_freq = '';
        if (!empty($this->is_rental)) {
            switch ($this->rent_period) {
                case 1:
                    $rent_freq = ' / <abbr title="Daily">day<abbr>';
                    break;
                case 2:
                    $rent_freq = ' / <abbr title="Weekly">wk.<abbr>';
                    break;
                case 3:
                    $rent_freq = ' / <abbr title="Monthly">mth.<abbr>';
                    break;
                default:
                    $rent_freq = ' / <abbr title="Yearly">p.a.<abbr>';
                    break;
            }
        }

        $currency_symbol = '&dollar; ';

        // If sale, look for price min/price max - RH 4/5/2021
        if (! empty($this->price_min) || ! empty($this->price_max))
        {
            // if price min and price max, use both instead of price
            if ($this->price_min && $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price_min,'USD');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'USD');
            }
            // if price max but no price min, check price is lower and use that
            elseif ($this->price && $this->price_max && $this->price < $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'USD');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'USD');
            }
            else
            // just use price
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'USD');
            }
        }
        else
            $price = $currency_symbol.$this->get_currency_conversions($this->price,'USD');

        $value = sprintf('%s%s%s',
            $qualifier.' ',
            $price,
            $rent_freq
        );

        return $value;
    }

    public function getDisplayPriceBBDAttribute()
    {
        if ($this->price_qualifier == 'POA') {            
            return 'Price on Application';
        }

        $qualifier = $this->price_qualifier;
         if ($this->price_qualifier == 'Default') {            
            $qualifier = '';
        }

        $rent_freq = '';
        if (!empty($this->is_rental)) {
            switch ($this->rent_period) {
                case 1:
                    $rent_freq = ' / <abbr title="Daily">day<abbr>';
                    break;
                case 2:
                    $rent_freq = ' / <abbr title="Weekly">wk.<abbr>';
                    break;
                case 3:
                    $rent_freq = ' / <abbr title="Monthly">mth.<abbr>';
                    break;
                default:
                    $rent_freq = ' / <abbr title="Yearly">p.a.<abbr>';
                    break;
            }
        }

        $currency_symbol = '&dollar; ';

        // If sale, look for price min/price max - RH 4/5/2021
        if (! empty($this->price_min) || ! empty($this->price_max))
        {
            // if price min and price max, use both instead of price
            if ($this->price_min && $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price_min,'BBD');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'BBD');
            }
            // if price max but no price min, check price is lower and use that
            elseif ($this->price && $this->price_max && $this->price < $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'BBD');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'BBD');
            }
            else
            // just use price
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'BBD');
            }
        }
        else
            $price = $currency_symbol.$this->get_currency_conversions($this->price,'BBD');

         $value = sprintf('%s%s%s',
            $qualifier.' ',
            $price,
            $rent_freq
        );

        return $value;
    }

    public function getDisplayPriceEURAttribute()
    {
        if ($this->price_qualifier == 'POA') {            
            return 'Price on Application';
        }

        $qualifier = $this->price_qualifier;
         if ($this->price_qualifier == 'Default') {            
            $qualifier = '';
        }

        $rent_freq = '';
        if (!empty($this->is_rental)) {
            switch ($this->rent_period) {
                case 1:
                    $rent_freq = ' / <abbr title="Daily">day<abbr>';
                    break;
                case 2:
                    $rent_freq = ' / <abbr title="Weekly">wk.<abbr>';
                    break;
                case 3:
                    $rent_freq = ' / <abbr title="Monthly">mth.<abbr>';
                    break;
                default:
                    $rent_freq = ' / <abbr title="Yearly">p.a.<abbr>';
                    break;
            }
        }

        $currency_symbol = '&euro; ';

        // If sale, look for price min/price max - RH 4/5/2021
        if (! empty($this->price_min) || ! empty($this->price_max))
        {
            // if price min and price max, use both instead of price
            if ($this->price_min && $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price_min,'EUR');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'EUR');
            }
            // if price max but no price min, check price is lower and use that
            elseif ($this->price && $this->price_max && $this->price < $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'EUR');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'EUR');
            }
            else
            // just use price
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'EUR');
            }
        }
        else
            $price = $currency_symbol.$this->get_currency_conversions($this->price,'EUR');

        $value = sprintf('%s%s%s',
            $qualifier.' ',
            $price,
            $rent_freq
        );

        return $value;
    }

    public function getDisplayPriceGBPAttribute()
    {
        if ($this->price_qualifier == 'POA') {            
            return 'Price on Application';
        }

         $qualifier = $this->price_qualifier;
         if ($this->price_qualifier == 'Default') {            
            $qualifier = '';
        }

        $rent_freq = '';
        if (!empty($this->is_rental)) {
            switch ($this->rent_period) {
                case 1:
                    $rent_freq = ' / <abbr title="Daily">day<abbr>';
                    break;
                case 2:
                    $rent_freq = ' / <abbr title="Weekly">wk.<abbr>';
                    break;
                case 3:
                    $rent_freq = ' / <abbr title="Monthly">mth.<abbr>';
                    break;
                default:
                    $rent_freq = ' / <abbr title="Yearly">p.a.<abbr>';
                    break;
            }
        }

        $currency_symbol = '&pound; ';

        // If sale, look for price min/price max - RH 4/5/2021
        if (! empty($this->price_min) || ! empty($this->price_max))
        {
            // if price min and price max, use both instead of price
            if ($this->price_min && $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price_min,'GBP');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'GBP');
            }
            // if price max but no price min, check price is lower and use that
            elseif ($this->price && $this->price_max && $this->price < $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'GBP');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'GBP');
            }
            else
            // just use price
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'GBP');
            }
        }
        else
            $price = $currency_symbol.$this->get_currency_conversions($this->price,'GBP');

         $value = sprintf('%s%s%s',
            $qualifier.' ',
            $price,
            $rent_freq
        );

        return $value;
    }

public function getDisplayPriceAEDAttribute()
    {
        if ($this->price_qualifier == 'POA') {            
            return 'Price on Application';
        }

         $qualifier = $this->price_qualifier;
         if ($this->price_qualifier == 'Default') {            
            $qualifier = '';
        }

        $rent_freq = '';
        if (!empty($this->is_rental)) {
            switch ($this->rent_period) {
                case 1:
                    $rent_freq = ' / <abbr title="Daily">day<abbr>';
                    break;
                case 2:
                    $rent_freq = ' / <abbr title="Weekly">wk.<abbr>';
                    break;
                case 3:
                    $rent_freq = ' / <abbr title="Monthly">mth.<abbr>';
                    break;
                default:
                    $rent_freq = ' / <abbr title="Yearly">p.a.<abbr>';
                    break;
            }
        }

        $currency_symbol = 'AED ';

        // If sale, look for price min/price max - RH 4/5/2021
        if (! empty($this->price_min) || ! empty($this->price_max))
        {
            // if price min and price max, use both instead of price
            if ($this->price_min && $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price_min,'AED');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'AED');
            }
            // if price max but no price min, check price is lower and use that
            elseif ($this->price && $this->price_max && $this->price < $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'AED');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'AED');
            }
            else
            // just use price
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'AED');
            }
        }
        else
            $price = $currency_symbol.$this->get_currency_conversions($this->price,'AED');

         $value = sprintf('%s%s%s',
            $qualifier.' ',
            $price,
            $rent_freq
        );

        return $value;
    }

public function getDisplayPriceCZKAttribute()
    {
        if ($this->price_qualifier == 'POA') {            
            return 'Price on Application';
        }
        
         $qualifier = $this->price_qualifier;
         if ($this->price_qualifier == 'Default') {            
            $qualifier = '';
        }

        $rent_freq = '';
        if (!empty($this->is_rental)) {
            switch ($this->rent_period) {
                case 1:
                    $rent_freq = ' / <abbr title="Daily">day<abbr>';
                    break;
                case 2:
                    $rent_freq = ' / <abbr title="Weekly">wk.<abbr>';
                    break;
                case 3:
                    $rent_freq = ' / <abbr title="Monthly">mth.<abbr>';
                    break;
                default:
                    $rent_freq = ' / <abbr title="Yearly">p.a.<abbr>';
                    break;
            }
        }

        $currency_symbol = 'Kč ';

        // If sale, look for price min/price max - RH 4/5/2021
        if (! empty($this->price_min) || ! empty($this->price_max))
        {
            // if price min and price max, use both instead of price
            if ($this->price_min && $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price_min,'CZK');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'CZK');
            }
            // if price max but no price min, check price is lower and use that
            elseif ($this->price && $this->price_max && $this->price < $this->price_max)
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'CZK');
                $price .= '&ndash;'.$this->get_currency_conversions($this->price_max,'CZK');
            }
            else
            // just use price
            {
                $price = $currency_symbol.$this->get_currency_conversions($this->price,'CZK');
            }
        }
        else
            $price = $currency_symbol.$this->get_currency_conversions($this->price,'CZK');

        $value = sprintf('%s%s%s',
            $qualifier.' ',
            $price,
            $rent_freq
        );

        return $value;
    }
    public function getArrayAddInfoAttribute()
    {
        $array_value = [];
        if(!empty($this->add_info))
        {
            $value = $this->add_info;
            if(!empty($this->internal_area))
            {
                $value = "Floor Area : ".$this->getDisplayInternalAreaAttribute().', '.$value;
            }
            if(!empty($this->land_area))
            {
                $value = "Land Size : ".$this->getDisplayLandAttribute().', '.$value;
            }

            $delimiters = array(',', ';', '.');
            $array_value  = $this->explode_array_delimiters($delimiters, $value);
        }
        return $array_value ;
    }

    /**
     *  Get The Additional Information as Two separate lists (First List)...
     */

    public function getListAddInfoFirstAttribute()
    {
        $array = $this->getArrayAddInfoAttribute();

        $len = count($array);

        $firsthalf = array_slice($array, 0, $len / 2);

        return $firsthalf;
    }

    public function getListAddInfoSecondAttribute()
    {
        $array = $this->getArrayAddInfoAttribute();

        $len = count($array);

        $firsthalf = array_slice($array, 0, $len / 2);
        $secondhalf = array_slice($array, $len / 2);

        return $secondhalf;
    }

    /**
     * Accessor:
     * The full Addition information tab for frontend of the property.
     *
     */
    public function getDisplayPropertyInfoAttribute()
    {
        
        $arrayMore=[];
        $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">Ref</div><div class="-add-info-item-data">'.$this->ref.'</div></div>';
        $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">Price</div><div class="-add-info-item-data">'.$this->display_price.'</div></div>';

        if(!empty($this->property_status)){
            $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">Status</div><div class="-add-info-item-data">'.$this->property_status.'</div></div>';
        }

        $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">Property Type</div><div class="-add-info-item-data">'.$this->PropertyTypeNameAll.'</div></div>';

        if(!empty($this->beds)){
            $beds = $this->beds>1?'Beds':'Bed';
            $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">'.$beds.'</div><div class="-add-info-item-data">'.$this->beds.'</div></div>';
        }
        if(!empty($this->baths)){
            $baths = $this->baths>1?'Baths':'Bath';
            $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">'.$baths.'</div><div class="-add-info-item-data">'.$this->baths.'</div></div>';
        }
        if(!empty($this->land_area)){
            $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">Land Size</div><div class="-add-info-item-data">'.$this->land_area.' '.$this->DisplayLandUnit.'</div></div>';
        }
        if(!empty($this->internal_area)){
            $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">Internal area</div><div class="-add-info-item-data">'.$this->internal_area.' '.$this->DisplayInternalUnit.'</div></div>';
        }
        if( $this->add_completion_date != null ){
            $arrayMore[]='<div class="-add-info-item"><div class="-add-info-item-label f-bold">Completion Date</div><div class="-add-info-item-data">'.$this->DisplayCompletionDate.'</div></div>';
        }

        return $arrayMore;
    }

    /**
     * Accessor:
     * The full Addition info of the property, likely used in the Admin
     *
     */

    public function getDisplayAddInfoAttribute()
    {
        $html = '';
        $value = $this->add_info;
        if(!empty($value))
        {
            $delimiters = array(',', ';', '.');

            $array = $this->explode_array_delimiters($delimiters, $value);

            if(count($array)){
                $html .= '<div class="add-info-container"><div class="row no-gutters">';
                foreach ($array as $value) {
                    $html .= '<div class="col-md-3 col-sm-3"><div class="item-ai d-flex h-100 align-items-center justify-content-center">';
                    $html .= $value;
                    $html .= '</div></div>';
                }
                $html .= '</div></div>';
            }
        }

        return $html;
    }

    public function getDisplayAddInfoArrayAttribute()
    {
        $value = $this->add_info;
        $array = [];
        if(!empty($value))
        {
            $delimiters = array(',', ';', '.');
            $array = $this->explode_array_delimiters($delimiters, $value);
        }
        return $array;
    }

    public function getDisplayAmenistiesInfoArrayAttribute()
    {
        $value = $this->add_amenities;
        $array = [];
        if(!empty($value))
        {
            $delimiters = array(',', ';', '.');
            $array = $this->explode_array_delimiters($delimiters, $value);
        }
        return $array;
    }

    public function getDisplayAddInfoDemo3Attribute()
    {
        $html = '';
        $value = $this->add_info;
        $ctr = 0;
        if(!empty($value)){
            $delimiters = array(',', ';', '.');

            $array = $this->explode_array_delimiters($delimiters, $value);

            if(count($array))
            {
                $html .= '<div class="property-specs"><div class="row ps-item">';
                foreach ($array as $value) {
                    $ctr++;
                    if( $ctr <= 6){

                        if($ctr%2 == 1){
                            $html .= '<div class="col-6"><div class="style-1"><i class="fas fa-circle"></i>';
                            $html .= $value;
                            $html .= '</div></div>';
                        }else{
                            $html .= '<div class="col-6"><div class="style-2">';
                            $html .= $value;
                            $html .= '</div></div>';
                        }

                    }
                }
                $html .= '</div></div>';
            }
        }

        return $html;
    }

    public function getDisplayAmenitiesArrayAttribute()
    {
        $value = $this->add_amenities;
        $array = [];
        if(!empty($value))
        {
            $delimiters = array(',', ';', '.');
            $array = $this->explode_array_delimiters($delimiters, $value);
        }
        return $array;
    }

    public function getModeDisplayAttribute()
    {
        $mode = ($this->is_rental) ? trans_fb('app.app_To_Rent', 'For Rent') : trans_fb('app.app_For_Sale', 'For Sale');
        return $mode;
    }

    /**
     * Accessor:
     * Get the URL for the property's details page
     *
     */
    public function getUrlAttribute()
    {

        if($this->name)
        {
            $slug = Str::slug($this->name);

            $value = sprintf('%s/%s/%s',
                'property', // property route
                $slug, // SEO-friendly
                $this->{$this->primaryKey} // property identifier
            );
        }
        else
        {
            // Format: /property/[NUMBER OF BEDS]-bed-[PROPERTY TYPE]-[FOR-SALE / TO-RENT]-in-[CITY]/[PROPERTY ID]
            $items = [];
            if ($this->beds) $items[] = $this->beds.' bed';
            $items[] = $this->PropertyTypeName;
            $items[] = ($this->is_rental) ? 'to rent' : 'for sale';
            if ($this->city) $items[] = 'in '.$this->city;
            $value = implode(' ', $items);
            $slug = Str::slug($value);

            $value = sprintf('%s/%s/%s',
                'property', // property route
                $slug, // SEO-friendly
                $this->{$this->primaryKey} // property identifier
            );
        }

        return url($value);
    }

    public function getPdfUrlAttribute()
    {
        $items = [];
        if ($this->beds) $items[] = $this->beds.' bed';
        $items[] = $this->PropertyTypeName;
        $items[] = ($this->is_rental) ? 'to rent' : 'for sale';
        if ($this->city) $items[] = 'in '.$this->city;
        $value = implode(' ', $items);
        $slug = Str::slug($value);

        $value = sprintf('%s/%s/%s',
            'property-pdf', // property route
            //$slug, // SEO-friendly
            'view',
            $this->{$this->primaryKey} // property identifier
        );

        return url($value);
    }

    /**
     * Accessor:
     * Get the status for display
     *
     */
    public function getStateDisplayAttribute()
    {
        $state_display = 'Available';
        $status = $this->status;
        $states_array = p_states();
        if ($status != '' || $status == '0') {
            if (array_key_exists($status, $states_array)) { // Check if key exists in array
                $state_display = $states_array[$status];
            }
        }
        

        return $state_display;
    }

    public function getPrimaryPhotoConstantAttribute()
    {
        $primaryPhotoConstant = url('property-primary-photo/'.$this->id);
        return $primaryPhotoConstant;
    }

    /*
    public function getGridPhotoAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) )
        {
            if(settings('store_s3') !== 'false')
            {
                // Returns Display Image from Both S3 / Local If Dual Image Hosting Setup...
                $exists = Storage::disk('s3')->exists($propertyMediaPhotos[0]->path);

                if($exists)
                {
                    $path = Storage::cloud()->url($propertyMediaPhotos[0]->path);
                }
                else
                {
                    $path = (Storage::exists($propertyMediaPhotos[0]->path)) ? storage_url($propertyMediaPhotos[0]->path) : default_thumbnail();
                }
            }
            else
            {
                if(settings('propertybase'))
                {
                    // If Propertybase, Load the URL String....
                    $path = $propertyMediaPhotos[0]->path;
                }
                else
                {
                    $path = (Storage::exists($propertyMediaPhotos[0]->path)) ? storage_url($propertyMediaPhotos[0]->path) : default_thumbnail();
                }
            }
        }
        else
        {
            $path = default_thumbnail();
        }

        if( count($propertyMediaPhotos) )
        {
            $dbFilename = basename($path);
            if(!Storage::exists('thumb-cache/property/'.$this->id.'/test')){
                $getRealPath = $path;
                $path_save_image = 'thumb-cache/property/'.$this->id.'/test';

                if(Storage::exists($path)){
                    $getRealPath = storage_url($path);
                }

                $image_resize = Image::make($getRealPath);
                $image_resize->orientate();
                $image_resize->resize(490, 320, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                Storage::disk('public')->put($path_save_image, $image_resize->stream());

            }

            if(Storage::exists('thumb-cache/property/'.$this->id.'/'.$dbFilename)){
                $path = storage_url('thumb-cache/'.$propertyMediaPhotos[0]->path);
            }
        }

        return $path;
    }
    */


    /**
     * Accessor:
     * Get the Primary Photo
     *
     */
    public function getPrimaryPhotoAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        $path = default_thumbnail();
        if( count($propertyMediaPhotos) )
        {
            if (preg_match('/^https?\:\/\//i', $propertyMediaPhotos[0]->path) || Storage::exists($propertyMediaPhotos[0]->path)){
                $path = storage_url($propertyMediaPhotos[0]->path);
            }
        }
        return $path;
    }

public function getSecondaryPhotoAttribute()
{
    $propertyMediaPhotos = $this->propertyMediaPhotos;

    if (count($propertyMediaPhotos) && !empty($propertyMediaPhotos[1])) {
        if (settings('store_s3') !== 'false') {
            $exists = Storage::disk('s3')->exists($propertyMediaPhotos[1]->path);

            if ($exists) {
                $path = Storage::cloud()->url($propertyMediaPhotos[1]->path);
            } else {
                $path = (Storage::exists($propertyMediaPhotos[1]->path)) ? storage_url($propertyMediaPhotos[1]->path) : default_thumbnail();
            }
        } else {
            if (settings('propertybase')) {
                $path = $propertyMediaPhotos[1]->path;
            } else {
                $path = (Storage::exists($propertyMediaPhotos[1]->path)) ? storage_url($propertyMediaPhotos[1]->path) : default_thumbnail();
            }
        }
        
        // Add WebP support
        $webpPath = $propertyMediaPhotos[1]->path . '.webp';
        if (Storage::exists($webpPath)) {
            $path = Storage::url($webpPath);
        }
    } else {
        $path = default_thumbnail();
    }

    return $path;
}


    public function getThirdPhotoAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[2]) )
        {
            if(settings('store_s3') !== 'false')
            {
                // Returns Display Image from Both S3 / Local If Dual Image Hosting Setup...
                $exists = Storage::disk('s3')->exists($propertyMediaPhotos[2]->path);

                if($exists)
                {
                    $path = Storage::cloud()->url($propertyMediaPhotos[2]->path);
                }
                else
                {
                    $path = (Storage::exists($propertyMediaPhotos[2]->path)) ? storage_url($propertyMediaPhotos[2]->path) : default_thumbnail();
                }
            }
            else
            {
                if(settings('propertybase'))
                {
                    // If Propertybase, Load the URL String....
                    $path = $propertyMediaPhotos[2]->path;
                }
                else
                {
                    $path = (Storage::exists($propertyMediaPhotos[2]->path)) ? storage_url($propertyMediaPhotos[2]->path) : default_thumbnail();
                }
            }

        }else{
            $path = default_thumbnail();
        }
        return $path;
    }

    public function getFourthPhotoAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[3]) )
        {
            if(settings('store_s3') !== 'false')
            {
                // Returns Display Image from Both S3 / Local If Dual Image Hosting Setup...
                $exists = Storage::disk('s3')->exists($propertyMediaPhotos[3]->path);

                if($exists)
                {
                    $path = Storage::cloud()->url($propertyMediaPhotos[3]->path);
                }
                else
                {
                    $path = (Storage::exists($propertyMediaPhotos[3]->path)) ? storage_url($propertyMediaPhotos[3]->path) : default_thumbnail();
                }
            }
            else
            {
                if(settings('propertybase'))
                {
                    // If Propertybase, Load the URL String....
                    $path = $propertyMediaPhotos[3]->path;
                }
                else
                {
                    $path = (Storage::exists($propertyMediaPhotos[3]->path)) ? storage_url($propertyMediaPhotos[3]->path) : default_thumbnail();
                }
            }

        }else{
            $path = default_thumbnail();
        }
        return $path;
    }



    public function getSecondaryPhotoFlagAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[1]) ){
            $flag = true;
        }else{
           $flag = false;
        }
        return $flag;
    }

    public function getThirdPhotoFlagAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[2]) ){
             $flag = true;
        }else{
            $flag = false;
        }
        return $flag;
    }

    public function getPrimaryPhotoExtAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        $extension = '';
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[0]) ){
            $extension = $propertyMediaPhotos[0]->extension;
        }
        return $extension;
    }


    public function getPrimaryPhotoOrientationAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[0]) ){
            if($propertyMediaPhotos[0]->orientation == 'portrait'){
                $flag = 'portrait';
            }else{
                $flag = 'landscape';
            }
        }else{
           $flag = 'portrait';
        }
        return $flag;
    }

    public function getSecondaryPhotoOrientationAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[1]) ){
            if($propertyMediaPhotos[1]->orientation == 'portrait'){
                $flag = 'portrait';
            }else{
                $flag = 'landscape';
            }
        }else{
           $flag = 'portrait';
        }
        return $flag;
    }

    public function getThirdPhotoOrientationAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[2]) ){
            if($propertyMediaPhotos[2]->orientation == 'portrait'){
                $flag = 'portrait';
            }else{
                $flag = 'landscape';
            }
        }else{
           $flag = 'portrait';
        }
        return $flag;
    }

    public function getFourthPhotoOrientationAttribute()
    {
        $propertyMediaPhotos = $this->propertyMediaPhotos;
        if( count($propertyMediaPhotos) && !empty($propertyMediaPhotos[3]) ){
            if($propertyMediaPhotos[3]->orientation == 'portrait'){
                $flag = 'portrait';
            }else{
                $flag = 'landscape';
            }
        }else{
           $flag = 'portrait';
        }
        return $flag;
    }

    public function getBedBathAreaAttribute()
    {
        $bedbatharea = [];

        if( !empty($this->beds) ){
            $bedbatharea[] = '<span>'.$this->beds.' '.trans_fb("app.app_Bed","Bed").' </span>';
        }
        if( !empty($this->baths) ){
            $bedbatharea[] = '<span>'.$this->baths.' '.trans_fb("app.app_Bath","Bath").'</span>';
        }
        if( !empty($this->internal_area) ){
            $bedbatharea[] = '<span>'.$this->internal_area.' '.$this->DisplayInternalUnit.'</span>';
        }

        $bedbathareaString = implode(' | ', $bedbatharea);

        return $bedbathareaString;
    }

    public function getBedBathAttribute()
    {
        $bedbatharea = [];

        if( !empty($this->beds) )
        {
            $bedbatharea[] = '<span>'.$this->beds.' '.trans_fb("app.app_Beds", "Bed").'</span>';
        }

        if( !empty($this->baths) )
        {
            $bedbatharea[] = '<span>'.$this->baths.' '.trans_fb("app.app_Bath", "Bath").'</span>';
        }

        $bedbathareaString = implode(' | ', $bedbatharea);

        return $bedbathareaString;
    }

    /**
     * Accessor:
     * The alt value for proeprty images
     *
     */
    public function getImageAltAttribute()
    {
        $property_type_txt = $this->PropertyTypeNamePlaceholder;
        $value = "$this->beds bed $property_type_txt For ".($this->is_rental ? 'Rent' : 'Sale')." in $this->city, $this->region";

        return $value;
    }

    public function locationList()
    {
        $locations_list = [];

        if(settings('overseas') == 1){
            $locations = ['street', 'town', 'city', 'complex_name', 'postcode'];
        }else{
            $locations = ['street', 'town', 'city', 'region', 'complex_name', 'postcode'];
        }

        foreach( $locations as $location){ //FIELD TO SEARCH
            $properties = Property::where([['status','>=', 0],['status','!=', 1],[$location, '!=', '']])->distinct($location)->get();
            if(!empty($properties)){

                foreach( $properties as $property){
                    if( !in_array($property->{$location}, $locations_list) ){
                        $locations_list[] = $property->{$location};
                    }
                }
            }
        }

        return $locations_list;
    }


    /**
     *  Property Seach
     */

     public function searchWhere($criteria = [], $dashboard = FALSE, $strict_limit = FALSE)
     {
        
        $check_shortlist = request()->segment(1);

        $admin_allowed = $this->admin_allowed;
        $custom_sort = false;
        $criteria = array_filter(array_intersect_key($criteria, array_flip($admin_allowed)));
        
        $where = [];

        if($dashboard)
        {
            if(empty($criteria['status']))
            {
                $where[] = ['status', '!=', 1]; // NOT ARCHIVE
            }

            if(Auth::user()->role_id == 3){
                $where[] = ['user_id',  Auth::user()->id];
            }

        }else{

            if(empty($criteria['status']))
            {
                // if($criteria['type'] === 'development'){
                //     $where[] = ['status', '===', 3];
                // }
                $where[] = ['status', '>=', 0];
                $where[] = ['status', '!=', 1]; // NOT ARCHIVE
            }
            else
            {
                // Handle any status filter, including Sold/Rented (16/17)
                $where[] = ['status', $criteria['status']];
            }
        }

        // dd($where);

        // For Shortlist Page
        if($check_shortlist == 'shortlist')
        {
            $shortlist_ip = App\Shortlist::shortlist_ip();
            $shortlist_property_ids = $shortlist_ip->pluck('property_id')->toArray();
            $where[] = ['is_development', 0];
        }

        $query = $this->with('user')->where($where);

        // dd($query->first());

        // For Shortlist Page
        if($check_shortlist == 'shortlist')
        {
            $query->whereIn('id', $shortlist_property_ids);
        }
        
        if(isset($criteria['for']) && $criteria['for']  === "development"){
            $query->where('is_development', 'y');
        }

        // Exclude ID's (Similar Properties)
        if(!empty($criteria['exclude_id']))
        {
            $query->where('id', '!=', $criteria['exclude_id']);
        }

        if(!empty($criteria['exclude_ids']))
        {
            $query->whereNotIn('id', explode(',', $criteria['exclude_ids']));
        }

        // Prepare for map search
        $lat_min = 0;
        $lat_max = 0;
        $lng_min = 0;
        $lng_max = 0;

        if(!empty($criteria['ne-lat']) && !empty($criteria['ne-lng']) && !empty($criteria['sw-lat']) && !empty($criteria['sw-lng'])){
            $lat_min = $criteria['sw-lat'];
            $lat_max = $criteria['ne-lat'];
            $lng_min = $criteria['sw-lng'];
            $lng_max = $criteria['ne-lng'];
        }

        // dd($criteria);

        foreach ($criteria as $key => $value)
        {
            // dd($criteria,$key,$value);
            switch($key)
            {
                case 'for':
                    $is_rental = ($value == 'rent') ? 1 : 0;
                    $query->where('is_rental', $is_rental);
                break;
                case 'in':
                    if($value == 'international' || $value == 'International'){
                        // For International properties, show properties outside of Dubai
                        $query->where('country', 'NOT LIKE', '%dubai%')
                              ->where('country', 'NOT LIKE', '%uae%')
                              ->where('country', 'NOT LIKE', '%united arab emirates%');
                    } else {
                        $query->where(function($query) use ( $value )
                        {
                            $query->orwhere([['name', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['ref', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['street', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['town', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['city', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['region', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['postcode', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['country', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['complex_name', 'LIKE', '%'.$value.'%']]);
                            $query->orwhere([['agent_notes', 'LIKE', '%'.$value.'%']]);
                        });
                    }
                break;
                case 'area':
                    if(!empty($value) && $value!='all') {
                        if($value=='others'){
                            //get other areas property that are not define in dropdown array
                            $areas = get_areas();
                            $areas = array_values($areas);
                            $query->where(function($query) use ( $areas )
                            {
                               $query->whereNotIn('street', $areas);

                               $query->orWhere(function($query) use ( $areas )
                                {
                                   $query->whereNotIn('town', $areas);
                                });
                                $query->orWhere(function($query) use ( $areas )
                                {
                                   $query->whereNotIn('city', $areas);
                                });
                                $query->orWhere(function($query) use ( $areas )
                                {
                                   $query->whereNotIn('region', $areas);
                                });
                                $query->orWhere(function($query) use ( $areas )
                                {
                                   $query->whereNotIn('complex_name', $areas);
                                });
                            });
                        } else {
                            $area = urldecode(trim($value));
                            $query->where(function($query) use ( $area )
                            {
                                $query->orwhere([['street', 'LIKE', '%'.$area.'%']]);
                                $query->orwhere([['town', 'LIKE', '%'.$area.'%']]);
                                $query->orwhere([['city', 'LIKE', '%'.$area.'%']]);
                                $query->orwhere([['region', 'LIKE', '%'.$area.'%']]);
                                $query->orwhere([['complex_name', 'LIKE', '%'.$area.'%']]);
                            });
                        }
                    }
                break;
                case 'property_type_id':
                    if (!empty($value)) {
                        $query->where(function($q) use ($value) {
                            $q->where('property_type_id', $value)
                              ->orWhere('property_type_ids', 'LIKE', '%"'.$value.'"%')
                              ->orWhere('property_type_ids', 'LIKE', '%,'.$value.',%')
                              ->orWhere('property_type_ids', 'LIKE', '%['.$value.']%')
                              ->orWhere('property_type_ids', 'LIKE', '%'.$value.'%');
                        });
                    }
                break;
                case 'property-type-ids':
                    $property_type_ids = is_array($value) ? $value : explode(',', $value);
                    $query->where(function($q) use ($property_type_ids) {
                        foreach($property_type_ids as $property_type_id){
                            $q->orWhere('property_type_id', $property_type_id)
                              ->orWhere('property_type_ids', 'LIKE', '%'.$property_type_id.'%');
                        }
                    });
                break;
                case 'category-ids':
                    $category_ids = is_array($value) ? $value : explode(',', $value);
                    $query->where(function($q) use ($category_ids) {
                        foreach($category_ids as $category_id){
                            $q->orWhere('category_id', $category_id)
                              ->orWhere('category_ids', 'LIKE', '%'.$category_id.'%');
                        }
                    });
                break;
                case 'is-brown-harris-stevens':
                    if($value == 'yes'){
                        $query->where('feed_id', 2);
                    }
                break;
                case 'property-type':
                    $property_type = PropertyType::findBySlug($value);
                    if($property_type){
                        $query->where(function($q) use ($property_type) {
                            $q->where('property_type_id', $property_type->id)
                              ->orWhere('property_type_ids', 'LIKE', '%'.$property_type->id.'%');
                        });
                    }
                break;
                case 'category':
                    $category = App\Models\Category::findBySlug($value);
                    if($category){
                        $query->where(function($q) use ($category) {
                            $q->where('category_id', $category->id)
                              ->orWhere('category_ids', 'LIKE', '%'.$category->id.'%');
                        });
                    }
                break;
                case 'community':
                    $community = App\Models\Community::findByName($value);
                    if($community){
                        $query->whereHas('communities', function($q) use ($community) {
                            $q->where('community_id', $community->id);
                        });
                    }
                break;
                case 'beds':
                    $query->where('beds', $value);
                break;
                case 'minbeds':
                    $query->where('beds', '>=', $value);
                break;
                case 'baths':
                    $query->where('baths', $value);
                break;
                case 'min-price':
                    $query->where('price', '>=', $value);
                break;
                case 'max-price':
                    $query->where('price', '<=', $value);
                break;
                case 'price_range':
                    $splitprice = explode('-',$value);
                    $query->where('price','>=',$splitprice[0])->where('price','<=',$splitprice[1]);
                break;
                case 'ref':
                    $query->where('ref', 'LIKE', '%'.$value.'%');
                break;
                case 'is_featured':
                    $query->where('is_featured', $value);
                break;
                case 'is_development':
                    $query->where('is_development', $value);
                break;
                case 'complex':
                    $complex = urldecode(trim($value));
                    $query->where('complex_name', 'LIKE', '%'.$complex.'%');
                break;
                case 'sort':
                    switch($value)
                    {
                        case 'most-recent':
                            $query->orderby('created_at', 'desc');
                            $custom_sort = true;
                        break;
                        case 'lowest-price':
                            $query->orderby('price', 'asc');
                            $custom_sort = true;
                        break;
                        case 'highest-price':
                            $query->orderby('price', 'desc');
                            $custom_sort = true;
                        break;
                        case 'name':
                            $query->orderby('name', 'asc');
                            $custom_sort = true;
                        break;
                    }
                break;
                case 'branch_id':
                    $where[] = ['branch_id', $value];
                    break;
                case 'polygon':
                    $where[] = ['latitude','>=' ,$lat_min];
                    $where[] = ['latitude','<=', $lat_max];
                    $where[] = ['longitude','>=', $lng_min];
                    $where[] = ['longitude','<=', $lng_max];
                break;
                default:
                    $where[] = [$key, $value];
                    break;
            }
        }

        if(!empty($criteria['sevenDays'])){
            $date_7days_before = date('Y-m-d H:i:s',strtotime('-7 days'));
            $date_now = date('Y-m-d H:i:s');
            $query->whereBetween('created_at', [$date_7days_before, $date_now]);
        }

        if(!empty($criteria['oneDay'])){
            $date_1day_before = date('Y-m-d H:i:s',strtotime('-1 days'));
            $date_now = date('Y-m-d H:i:s');
            $query->whereBetween('created_at', [$date_1day_before, $date_now]);
        }

        // if(!empty($criteria['popular'])){
        //     $query->orderBy('views', 'desc');
        //     $custom_sort = true;
        // }

        if (!$custom_sort)
        {
            // Only apply this ordering when not explicitly searching for status 16 or 17
            if(empty($criteria['status']) || ($criteria['status'] != '16' && $criteria['status'] != '17')) {
                // Order properties with status 16 (Sold / Request Similar) and 17 (Rented / Request Similar) to appear last
                $query->orderByRaw('CASE WHEN status IN (16, 17) THEN 1 ELSE 0 END ASC')
                      ->orderBy('updated_at', 'desc');
            } else {
                // If explicitly searching for status 16 or 17, just use regular ordering
                $query->orderBy('updated_at', 'desc');
            }
        }

        if(!empty($criteria['is-brown-harris-stevens']) && $criteria['is-brown-harris-stevens'] == 'yes'){
            $query->where('feed_id', 2);
        }

        if(isset($criteria['status'])){
            $query->where('status', $criteria['status']);
        }

        if(isset($criteria['is_admin_approval'])) {
            $boolval = $criteria['is_admin_approval'] == 'true' ? 1 : 0;
            $query->where('admin_approval', $boolval);
        }

        if ($strict_limit)
        {
            // I added this for "Similar Properties" where we only want three
            $properties = $query->paginate($strict_limit);
        }
        else
        {
            if(isset($criteria['status']) && $criteria['status'] == '1')
            {
                $properties = $query->withTrashed()->paginate(12);
            }
            else
            {
                if(isset($criteria['max']))
                {
                    $properties = $query->paginate($criteria['max']);
                }
                else
                {
                    $properties = $query->paginate(12);
                }
            }
        }

        return $properties;
     }

     protected static function boot()
     {
         parent::boot();
     
         static::updating(function ($model) {
             // If the status is Sold / Request Similar or Rented / Request Similar
             if (in_array($model->status, [16, 17])) {
                 // Disable timestamp update
                 $model->timestamps = false;
             }
         });
     }

    public function zooplatag()
    {
        return $this->hasOne('App\Models\ZooplaEtag', 'property_id', 'id');
    }

    function explode_array_delimiters( $delimiters, $string )
    {
        return explode( chr( 1 ), str_replace( $delimiters, chr( 1 ), $string ) );
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function getDescriptionAttribute()
    {
        $field_name = 'description';
        if(settings('translations'))
        {
            $field_lang = $this->translations()->where('language', config('app.locale'))->pluck($field_name)->first();
            if( $this->translations()->count() > 0 &&
                config('app.locale') !== $this->get_default_language() &&
                !empty($field_lang)
                )
            {
                return $field_lang;
            }
            else
            {
                return $this->attributes[$field_name];
            }
        }
        else
        {
            return $this->attributes[$field_name];
        }
    }


    public function valuation()
    {
        return $this->hasOne('App\Models\ClientValuation', 'client_valuation_id', 'client_valuation_id');
    }

    public function development()
    {
        return $this->hasOne('App\Models\Development', 'development_id', 'development_id');
    }
}
