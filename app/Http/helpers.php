<?php
use App\Dashboard;
use App\Metadata;
use App\Models\Languages;
use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Traits\TranslateTrait;
use App\Models\Community;
use App\PropertyType;
use App\Property;
use App\User;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

/*-------------------------------------------------------------
*
* ** Theme logic
*
--------------------------------------------------------------*/
if (! function_exists('themeOptions')) {
    function themeOptions()
    {
        $themeDirectory = 'demo1';

        return $themeDirectory;
    }
}


if (! function_exists('settings')) {
    function settings($key, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            if (Schema::hasTable('settings')) {
                $settings = DB::table('settings')
                    ->pluck('val', 'name')
                    ->toArray();
            } else {
                $settings = [];
            }
        }

        return $settings[$key] ?? $default;
    }
}

if (! function_exists('themeAssets')) {
    function themeAsset($uri=''){
        $themeOptions = themeOptions();

        $a = array_filter(explode('/', $uri));

        if (reset($a) == 'assets') {
            array_shift($a);
        }

        $uri = implode('/', $a);
        $uri = ($uri != '' ? '/'.$uri : '');
        $url = url('assets/'.$themeOptions.$uri);

        return $url;
    }
}

/*-------------------------------------------------------------
* ** Demo2 search title
--------------------------------------------------------------*/
if (! function_exists('demo2SearchTitle')) {
    function demo2SearchTitle($criteria, $properties=null)
    {
        $in = true;
        $title= [];
        $for = ucwords(post_criteria($criteria, 'for'));
        $for = trans_fb('app.app_'.$for, $for);

        if(!empty($criteria['in'])){
            $location = ucwords(post_criteria($criteria, 'in'));
        }else{
            $location = settings('default_location');
        }

        if (!empty($properties) && empty($properties->total()))
        {
            $title[] = 'Sorry, no properties found. <a href="'.url('property-for-sale').'">Start a new search</a>';
            $title[] = trans_fb('app.app_For', 'For');
        }
        else
        {
            $title[] = '<span>'.$properties->total().' found</span>';
            $title[] = trans_fb('app.app_For', 'for');
        }

        if(isset($criteria['category']))
        {
            $category = $criteria['category'];

            if($category == 'brown-harris-stevens')
            {
                $location = "Worldwide";
                $in = false;
            }
        }

        $title[] = $for;
        if($in)
        {
            $title[] = trans_fb('app.app_In', 'in');
        }
        $title[] = $location;

        $searchTitle = implode(' ',$title);

        if(count($criteria) <= 2)
        {
            $searchTitle = 'Property Results in '.settings('default_location');
        }

        return $searchTitle;

    }
}

/*-------------------------------------------------------------
* ** Demo3 search title
--------------------------------------------------------------*/
if (! function_exists('demo3SearchTitle')) {
    function demo3SearchTitle($criteria, $properties=null)
    {
        $title= [];
        $for = ucwords(post_criteria($criteria, 'for'));
        $propertyType =  !(empty(post_criteria($criteria, 'property_type'))) ?  ucwords(post_criteria($criteria, 'property_type')) : 'Properties';

        if(!empty($criteria['in'])){
            $location = ucwords(post_criteria($criteria, 'in'));
        }else{
            $location = settings('default_location');
        }

        $title[] = $propertyType." for ".$for;
        $title[] = 'in';
        $title[] = $location;

        if (!empty($properties) && !empty($properties->total())){
            $title[] = '<span>('.$properties->total().' found)</span>';
        }

        $searchTitle = implode(' ',$title);

        return $searchTitle;
    }
}

/*-------------------------------------------------------------
* ** Demo4 search title
--------------------------------------------------------------*/

if (! function_exists('demo4SearchTitle')) {
    function demo4SearchTitle($criteria, $properties=null)
    {
        if($properties && $properties->count() > 0 )
        {
            $title = [];

            if (!empty($properties) && !empty($properties->total()))
            {
                $title[] = $properties->total();
            }

            $propertyType =  post_criteria($criteria, 'property_type');

            if(empty($propertyType))
            {
                $title[] = pluralise(2, 'Property');
            }
            else
            {
                $title[] = ucwords(str_replace('-', ' ', pluralise(2,$propertyType)));
            }

            $title[] = "Found";

            $searchTitle = implode(' ',$title);
        }
        else
        {
            $searchTitle = 'No Properties Found';
        }



        return $searchTitle;
    }
}

if (! function_exists('searchBreadcrumb')) {
    function searchBreadcrumb($criteria, $properties='')
    {
        $links = [];
        $links[] = "Home";

        $propertyType =  post_criteria($criteria, 'property_type');

        if(empty($propertyType))
        {
            $links[] = 'Search';
        }
        else
        {
            $links[] = ucwords(str_replace('-', ' ', pluralise(2,$propertyType)));
        }

        return $links;
    }
}

/**
 *
 *
 * @param  string  $string
 */
if (! function_exists('tidy_commas')) {
    function tidy_commas()
    {
    	// Each location as an argument in order
        // We'll also remove false values as this is only being used by the property detail page
    	$locs = func_get_args();
    	foreach ($locs as $index => $loc)
        {
    		if (trim($loc) == false) { unset($locs[$index]); }
    	}
    	return implode(', ', $locs);
    }
}

/**
 * This will auto add 'admin' in the url.
 *
 * @param  string  $string
 */
if (! function_exists('admin_url')) {
    function admin_url($string='')
    {
        $url = url('public/admin/'.$string);
        return $url;
    }
}

/*-------------------------------------------------------------
*
* ** meta description generate from html description LINK
*
--------------------------------------------------------------*/
if (! function_exists('makeMetaDescription')) {
    function makeMetaDescription($html, $length = 160)
    {
        // 1. Decode HTML entities (&lt; &gt; &quot; etc.)
        $text = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // 2. Remove HTML tags
        $text = strip_tags($text);

        // 3. Normalize whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // 4. Shorten without breaking words
        if (mb_strlen($text) > $length) {
            $text = mb_substr($text, 0, $length);
            $text = mb_substr($text, 0, mb_strrpos($text, ' ')) . '…';
        }

        return $text;
    }
}


if(! function_exists('processImage')){
    function processImage($file, $path, $width = 1200, $height =  null, $quality = 70, $extension = 'webp')
    {
        $manager = new ImageManager(['driver' => 'gd']);

        // Ensure valid quality range
        $quality = max(0, min(100, (int)$quality));

        $directory = storage_path('app/public/' . trim($path,'/'));

        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }

        $image = $manager->make($file->getRealPath());

        $image->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $filename = uniqid().'.'.$extension;

        $savePath = $directory.'/'.$filename;

        $image->encode($extension, $quality)->save($savePath);

        // Medium
        $manager->make($file)
            ->fit(700, 450)
            ->encode('webp', 75)
            ->save($directory.'/medium_'.$filename);

        // Small
        $manager->make($file)
            ->fit(400, 260)
            ->encode('webp', 75)
            ->save($directory.'/small_'.$filename);

        $srcset = [$directory.'/small_'.$filename,$directory.'/medium_'.$filename,$filename];

        return $filename;
    }
}

if(! function_exists('OptimizeImgLinks')){
    function OptimizeImgLinks($path = null){
        if($path){
            $explodepath = explode('/',$path);
            $filename = end($explodepath); // original file name
            $version = ['400w'=>'small','600w'=>'medium'];
            $versionLinks = '';
            foreach($version as $key=>$single){
                $newpath = $explodepath;
                $versionfile = $single.'_'.$filename;
                $newpath[count($newpath)-1] = $versionfile;
                $pathlink = implode('/',$newpath);
                $versionLinks = $versionLinks.($key !== '400w' ? ', ' : '').storage_url($pathlink).' '.$key;
            }
            $versionLinks = $versionLinks.', '.storage_url(implode('/',$explodepath)).' 1000w';
            return $versionLinks;
        }
        return;
    }
}


if(! function_exists('convertToWebp')){
    function convertToWebp($imagePath, $quality = 75)
    {
        if (!file_exists($imagePath)) {
            return false;
        }

        $webpPath = pathinfo($imagePath, PATHINFO_DIRNAME) . '/' .
                    pathinfo($imagePath, PATHINFO_FILENAME) . '.webp';
        
        $manager = new ImageManager(['driver' => 'gd']);

        $manager->make($imagePath)
            ->resize(1200, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->encode('webp', 75)
            ->save($webpPath);
        
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        return $webpPath;
    }
}

/**
 * This will auto add 'storage' in the url.
 *
 * @param  string  $string
 */
if (! function_exists('storage_url')) {
    function storage_url($string='')
    {
        if($string == themeAsset('images/placeholder/large.jpg')){
            $url = $string;
        }elseif(preg_match('/^https?\:\/\//i', $string)){ // absolute url
            $url = $string;
        }else{
            $url = url('public/storage/'.$string);
        }
        return $url;
    }
}

/**
 * This will compare the two variable.
 *
 * @param  string  $compare1
 * @param  string  $compare2
 */
if (! function_exists('set_selected')) {
    function set_selected($compare1, $compare2)
    {
        return (strtolower($compare1) === strtolower($compare2)) ? ' selected' : '';
    }
}

/**
 * Admin date format.
 *
 * @param  string  $string
 */
if (! function_exists('admin_date')) {
    function admin_date($string='')
    {
        if(!empty($string)){
            //$string = date('d/m/Y, g:i a',strtotime($string));
            $string = date('F jS Y', strtotime($string));
        }

        return $string;
    }
}


/**
 * This will take active class base on the segment.
 *
 * @param  string  $compare1
 * @param  int  $segment
 */
if (! function_exists('active_class')) {
    function active_class($compare1='', $segment=1)
    {
        if( is_array($compare1) )
        {
            $compare2 = request()->segment($segment);
            $compare2 = strtolower($compare2);
            $compare1 = array_map('strtolower', $compare1);
            return (in_array($compare2, $compare1)) ? 'active' : 'not-active';
        }
        else
        {
            $compare2 = request()->segment($segment);
            return (strtolower($compare1) === strtolower($compare2)) ? 'active' : 'not-active';
        }
    }
}

if (! function_exists('required_label')) {
function required_label ()
    {
        return '<strong class="required" title="This field is mandatory">*</strong>';
    }
}

/*
* Display the alert message from flash
*/
if (! function_exists('get_flash_alert')) {
function get_flash_alert()
    {
      $classes = ['danger','success','info','warning'];
      $alert_html = false;

      foreach ($classes as $class) {
        if (request()->session()->has('message_'.$class)) {
          $msg = request()->session()->get('message_'.$class);
          $alert_html = '<div class="alert alert-'.$class.' alert-dismissible show" role="alert">
              '.$msg.'
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>';
        }
      }

      return $alert_html;
    }
}

if (! function_exists('prepare_inputs')) {
    function prepare_inputs ($inputs)
    {
        unset($inputs['_token']);
        unset($inputs['_method']);
        unset($inputs['action']);
        return $inputs;
    }
}

/*-------------------------------------------------------------
*
* ** CONFIGS : Jan: can be transferred in the best way
*
--------------------------------------------------------------*/

if (! function_exists('min_price')) {
    function min_price(){
        return 0;
    }
}

if (! function_exists('max_price')) {
    function max_price(){
        return 500000000;
    }
}

if (! function_exists('format_date_v1')) {
    function format_date_v1($date){
        $strtotime = strtotime($date);
        $formatted = date('d/m/Y, g:i a', $strtotime);
        return $formatted;
    }
}

if (! function_exists('e_states')) {
    function e_states(){
        $data = [
                   // -2 => 'Removed',

                   'Inactive' => 'Inactive',
                   'Hot' => 'Hot',
                   'Normal' => 'Normal',
                   'Close' => 'Close',
                   'Scam' => 'Scam',
                ];
        return $data;
    }

    function m_states(){
        $data = [
                   // -2 => 'Removed',

                   '' => 'Status',
                   'Inactive' => 'Inactive',
                   'Hot' => 'Hot',
                   'Normal' => 'Normal',
                   'Close' => 'Close',
                   'Scam' => 'Scam',
                ];
        return $data;
    }

    function gm_select(){
        $data = [
                   'all' => 'All',
                   '' => 'None',
                   'Inactive' => 'Inactive',
                   'Hot' => 'Hot',
                   'Normal' => 'Normal',
                   'Close' => 'Close',
                   'Scam' => 'Scam',
                ];
        return $data;
    }
}

if (! function_exists('p_states')) {
    function p_states(){
        $status = DB::table('property_statuses')->select('id','name')->get()->pluck('name','id')->toArray();
        return $status;
    }
}

if (! function_exists('p_states_search')) {
    function p_states_search($default='Please select...'){
        $status = DB::table('property_statuses')->select('id','name')->get()->pluck('name','id');
        $data = $status->put('',$default)->toArray();    
        return $data;
    }
}



if (! function_exists('p_category')) {
    function p_category($sale=true, $default='Please select...'){

        if($sale){
            $data = [
                        '' => $default,
                        'luxury-listing' => 'Luxury Listing',
                        'residential-properties-land' => 'Residential Properties Land',
                        'commercial-properties' => 'Commercial Properties',
                        'brown-harris-steven' => 'Brown Harris Steven',

                        'beachfront' => 'Beachfront',
                        'beachview' => 'Beach View',
                        'gatedcommunity' => 'Gated Community',
                        'activelifestyle' => 'Active Lifestyle',
                    ];
        }else{
            $data = [
                        '' => $default,
                        'luxury-listing' => 'Luxury Listing',
                        'residential-properties-land' => 'Residential Properties Land',
                        'commercial-properties' => 'Commercial Properties',
                        'vacation-rentals' => 'Vacation Rentals',

                        'beachfront' => 'Beachfront',
                        'beachview' => 'Beach View',
                        'gatedcommunity' => 'Gated Community',
                        'activelifestyle' => 'Active Lifestyle',
                    ];
        }


        return $data;
    }
}



if (! function_exists('p_fieldTypes')) {
    function p_fieldTypes($default='Please select...'){
        $data = [''=> $default, 0 => 'Sale', 1 => 'Rent' ];
        return $data;
    }
}

if (! function_exists('p_fieldTypes_no_default')) {
    function p_fieldTypes_no_default()
    {
        $setting = settings('sale_rent');

        switch ($setting)
        {
            case "sale_rent":
                $data = [0 => trans_fb('app.app_Sale', 'Sale'), 1 => trans_fb('app.app_Rent', 'Rent')];
            break;
            case "sale":
                $data = [0 => 'Sale'];
           break;
           case "rent":
               $data = [0 => 'Rent'];
           break;
           default:
                $data = [0 => 'Sale', 1 => 'Rent'];
        }

        return $data;
    }
}

if (! function_exists('p_fieldTypesSearch')) {
    function p_fieldTypesSearch($default='Please select...')
    {
        $data = [''=> $default, 'sale' => 'Sale', 'rent' => 'Rent' ];
        return $data;
    }
}

if (! function_exists('p_statuses')) {
    function p_statuses(){
        $data = [''=>'Please select...', 'Available'=>'Available', 'SSTC'=>'SSTC', 'Under Offer'=>'Under Offer', 'Sold'=>'Sold'];
        return $data;
    }
}

if (! function_exists('p_priceQualifiers')) {
    function p_priceQualifiers(){
        $data = [''=>'Please select...','Default'=>'Default', 'POA'=>'Price on Application', 'Guide Price'=>'Guide Price', 'Fixed Price'=>'Fixed Price', 'Offers in excess of'=>'Offers in excess of', 'OIRO'=>'OIRO', 'Sale by Tender'=>'Sale by Tender', 'From'=>'From', 'Shared Ownership'=>'Shared Ownership', 'Offers over'=>'Offers over', 'Part Buy'=>'Part Buy', 'Part Rent'=>'Part Rent', 'Shared Equity'=>'Shared Equity', 'Coming Soon'=>'Coming Soon', 'Starting Price'=>'Starting Price'];
        return $data;
    }
}


if (! function_exists('p_rentPeriod')) {
    function p_rentPeriod(){
        $data = [4 => 'Yearly',3=>'Monthly',1=>'Daily',2=>'Weekly'];
        return $data;
    }
}

if (! function_exists('p_beds_frontend')) {
    function p_beds_frontend($default='Please select...'){
        $data = [];
        $data[''] = $default;
        //$data['Studio'] = 'Studio';
       /* foreach(range (1, 6) as $num){
            $text = ($num==1)?' Bedroom ':' Bedrooms ';
            $data[$num] = $num.$text.'+';
        }*/
        $max = Property::max('beds');
        $min = Property::min('beds');
        foreach(range ($min, $max) as $num){
            $text = ($num==1)?' Bedroom ':' Bedrooms ';
            $data[$num] = $num.$text.'+';
        }
        return $data;
    }
}

if (! function_exists('p_baths_frontend')) {
    function p_baths_frontend(){
        $data = [];
        $data[''] = 'Please select...';
        foreach(range (1, 20) as $num){
            $data[$num] = $num;
        }
        return $data;
    }
}

if (! function_exists('p_beds')) {
    function p_beds($default='Please select...'){
        $data = [];
        $data[''] = $default;
        foreach(range (0, 20) as $num){
            $data[$num] = $num;
        }
        return $data;
    }
}

if (! function_exists('p_baths')) {
    function p_baths($default='Please select...'){
        $data = [];
        $data[''] =  $default;
        foreach(range (0, 20) as $num){
            $data[$num] = $num;
        }
        return $data;
    }
}

if (! function_exists('u_states')) {
    function u_states(){
        $data = [ 0 => 'Inactive', 1 => 'Active' ];
        return $data;
    }
}


if (! function_exists('get_ptype')) {
   function get_ptype ()
{
    return Cache::remember('property_types', 60, function () {
        $propertyType = PropertyType::get();
        $data = [];
        if(!empty($propertyType) ){
            foreach($propertyType as $row){
                $data[$row->id] = $row->name;
            }
        }

        return $data;
    });
}
}

if (! function_exists('ptype_slug_by_id')) {
    function ptype_slug_by_id()
    {
        $propertyType = PropertyType::get();
        $data = [];
        if(!empty($propertyType) ){
            foreach($propertyType as $row){
                $data[$row->id] = $row->slug;
            }
        }
        return $data;
    }
}

if (! function_exists('ptype_id_by_slug')) {
    function ptype_id_by_slug()
    {
        $propertyType = PropertyType::get();
        $data = [];
        if(!empty($propertyType) ){
            foreach($propertyType as $row){
                $data[$row->slug] = $row->id;
            }
        }
        return $data;
    }
}

if (! function_exists('get_ptype_id_by_slug')) {
    function get_ptype_id_by_slug($slug)
    {
        $id = '';
        $ptype_id_by_slug = ptype_id_by_slug();
        if (array_key_exists($slug,$ptype_id_by_slug)){
            $id = $ptype_id_by_slug[$slug];
        }
        return $id;
    }
}

if (! function_exists('get_ptype_slug_by_id')) {
    function get_ptype_slug_by_id($id)
    {
        $slug = '';
        $ptype_id_by_slug = ptype_slug_by_id();
        if (array_key_exists($id,$ptype_id_by_slug)){
            $slug = $ptype_id_by_slug[$id];
        }
        return $slug;
    }
}


if (! function_exists('prepare_dropdown_ptype')) {
    function prepare_dropdown_ptype ($array=[], $default='Please select...')
    {
        $data = [];
        if(!empty($array) ){
            $data[''] = $default;
            foreach($array as $row){
                $data[$row->id] = $row->name;
            }
        }

        return $data;
    }
}

if (! function_exists('prepare_dropdown_ptype_slug')) {
    function prepare_dropdown_ptype_slug ($array=[], $default='Please select...')
    {
        $data = [];
        if(!empty($array) ){
            $data[''] = $default;
            foreach($array as $row){
                $data[$row->slug] = $row->name;
            }
        }

        return $data;
    }
}


if (! function_exists('prepare_criteria')) {
    function prepare_criteria($request, $fields)
    {
        // dd($request->type);
        $criteria = [];
        $criteria['type'] = $request->type;
        $criteria['for'] = $request->type; // Yep - RH 6/1/19

        // dd($criteria);
        if($request->property_type != 'property')
        {
            $criteria['property_type'] = $request->property_type;
        }

        if(!empty($request->any)){
            //Parse {any}
            $any = explode('/', $request->any);
            $flag = 0;
            foreach($any as $value){
                if($flag == 1){
                    $criteria[$field] = $value;
                    $flag=0;
                    $field='';
                }
                if( in_array($value, $fields) ){
                     $flag=1;
                     $field=$value;
                }
            }
        }

        if(!empty($request->sort)){
            $criteria['sort'] = $request->sort;
        }

        if(!empty($criteria['in'])){
            $criteria['in'] = ucwords(urldecode($criteria['in']));
        }

        if (! empty($criteria['property_type']) && empty($criteria['property-type'])) {
            $criteria['property-type'] = $criteria['property_type'];
        }

        if(!empty($criteria['property-type'])){
            $type_array = explode('-xx-', $criteria['property-type']);
            $criteria['property-type-slugs'] = $type_array;
            $type_array_store = [];
            if(count($type_array)){
                foreach($type_array as $type_item){
                    $propertyType = PropertyType::findBySlug($type_item);
                    if ($propertyType)
                    {
                        $type_array_store[] = $propertyType->id;
                    }
                }
                $criteria['property-type-ids'] = $type_array_store;
            }
        }

        if(!empty($criteria['category'])){
            $type_array = explode('-xx-', $criteria['category']);
            $criteria['category-slugs'] = $type_array;
            $type_array_store = [];
            if(count($type_array)){
                foreach($type_array as $type_item){
                    $type_item = remapPropertyTypes($type_item);
                    $propertyType = PropertyType::findBySlug($type_item);
                    if ($propertyType)
                    {
                        $type_array_store[] = $propertyType->id;
                    }
                }
                $criteria['category-ids'] = $type_array_store;
            }
        }

        return $criteria;
    }
}

if (! function_exists('post_criteria')) {
    function post_criteria($criteria, $field)
    {
        $value = '';
        if( array_key_exists($field, $criteria) ){
            $value = urldecode($criteria[$field]);
        }
        return $value;
    }
}

/**
 * Modify URL
 * I built this purely for the Sort By function - RH 7/1/19
 **/
if (! function_exists('modify_url')) {
    function modify_url($params = [])
    {
        $url = url()->current();
        $segments = explode('/', $url);
        foreach ($params as $key => $value) {
            $index = array_search($key, $segments);
            if ($index !== false) {
                // modify in url
                if ($value === false) {
                    // remove from url
                    unset($segments[$index]);
                    unset($segments[$index + 1]);
                } else {
                    $segments[$index + 1] = $value;
                }
            } else {
                // add to url
                $segments[] = $key;
                $segments[] = $value;
            }
        }
        return implode('/', $segments);
    }
}

/*-------------------------------------------------------------
*
* ** DEFAULT PHOTO
*
--------------------------------------------------------------*/
if (! function_exists('default_thumbnail')) {

    function default_thumbnail(){
        return themeAsset('images/placeholder/large.jpg');
    }

}

/*-------------------------------------------------------------
*
* ** TOP COUNTER
*
--------------------------------------------------------------*/
if (! function_exists('get_top_counter')) {
    function get_top_counter(){

        $dashboard = new Dashboard();
        $data['totalProperties'] = $dashboard->totalProperties();
        $data['propertiesHas3Enquiries'] = $dashboard->propertiesHas3Enquiries();
        $data['sevenDaysProperties'] = $dashboard->sevenDaysProperties();
        $data['totalActiveProperties'] = $dashboard->totalActiveProperties();
        $data['totalInActiveProperties'] = $dashboard->totalInActiveProperties();
        $data['totalSubscriber'] = $dashboard->totalSubscriber();
        $data['sevenDaysSubscriber'] = $dashboard->sevenDaysSubscriber();
        $data['sevenDaysEnquiries'] = $dashboard->sevenDaysEnquiries();
        $data['thirtyDaysEnquiries'] = $dashboard->thirtyDaysEnquiries();
        $data['totalwhatsappclicks'] = $dashboard->totalwhatsappclicks();


        return $data;
    }
}

/*-------------------------------------------------------------
*
* ** ALTLERNATIVE LINK
*
--------------------------------------------------------------*/
if (! function_exists('adminAlterLink')) {
    function adminAlterLink($counter=0, $url1='', $url2=''){
        $url = '';
        if($counter>0){
            $url = admin_url($url1);
        }elseif($counter==0 || empty($counter)){
            $url = admin_url($url2);
        }
        return $url;
    }
}

/*-------------------------------------------------------------
*
* ** METADATA
*
--------------------------------------------------------------*/
if (! function_exists('get_metadata')) {

    function get_metadata($object, $type = 'page'){

        $meta = new stdClass();

        switch ($type) {
            case 'search':
                // Title: [PROPERTY TYPE] [FOR SALE / TO RENT] in [SEARCHED-LOCATION] | [COMPANY NAME]
                // Description: Find your dream [PROPERTY TYPE] for sale in [SEARCHED-LOCATION] with [TARGET-LOCATION]'s leading Estate Agency, [COMPANY NAME]. View our range of houses, villas & apartments for sale in [SEARCHED-LOCATION] today.
                $type = !empty($object['for']) && $object['for'] != 'sale' ? 'To Rent' : 'For Sale';
                $type = empty($object['for']) && !empty($object['community']) ? '' : $type;

                $property = !empty($object['community']) ? 'Properties' : 'Property';

                $p_community = '';
                if(!empty($object['community'])){
                    $p_communities = p_communities('' ,FALSE);
                    $p_community = !empty($p_communities[$object['community']]) ? $p_communities[$object['community']].' -' : '';
                }

                $property_type = !empty($object['property_type_name']) ? $object['property_type_name'] : $property;
                $in = !empty($object['in']) ? $object['in'] : settings('default_location', config('app.default_location'));
                $target_location = settings('default_location', config('app.default_location'));
                $site_name = settings('site_name', config('app.name'));

                $meta->title = "$property_type $type in $p_community $in | $site_name";
                $meta->description = "Find your dream $property_type $type in $in ".($in != $target_location ? "with ".$target_location : false)."'s leading Estate Agency, $site_name. View our range of houses, villas & apartments for sale in $in today.";
                $meta->image = themeAsset('images/logos/meta.jpg');


                break;
            case 'property':
                // Title: [NUMBER OF BEDS] [PROPERTY TYPE] [FOR SALE / TO RENT] in [CITY] | [TARGET LOCATION]
                // Description: [H1 of the page] Search [COMPANY NAME] for the best [PAGE PROPERTY TYPE]s [FOR SALE / TO RENT] in [TARGET-LOCATION] today!
                $beds = !empty($object->beds) ? $object->beds.' Bed' : false;
                $property_type = !empty($object->property_type_id) ? App\PropertyType::find($object->property_type_id) : false;
                $property_type_txt = !empty($property_type) ? $property_type->name : 'Property';
                $type = !empty($object->is_rental) ?  'To Rent' : 'For Sale';
                $in = !empty($object->town) ? $object->town : false;
                $in = !empty($object->city) ? $object->city : $in;
                $target_location = !empty($in) ? $in : settings('default_location', config('app.default_location'));
                $default_location = settings('default_location', config('app.default_location'));
                $site_name = settings('site_name', config('app.name'));

                if($object->name)
                {
                    $meta->title = " $beds $property_type_txt - $object->name $type in $in | $default_location";
                }
                else
                {
                    $meta->title = "$beds $property_type_txt $type in $in | $default_location";
                }

                $meta->description = "{$object->details_headline}. Search $site_name for the best {$property_type_txt}s $type in $target_location today!";
                $meta->image = themeAsset('images/logos/meta.jpg'); // temporary

                break;

            case 'home':

                if(settings('sale_rent') == 'sale_rent'){
                    $type = 'For Sale / To Rent';
                }elseif(settings('sale_rent') == 'rent'){
                    $type = 'To Rent';
                }else{
                    $type = 'For Sale';
                }

                $site_name = settings('site_name', config('app.site_name'));
                $location = settings('default_location', config('app.default_location'));
                $meta->title = 'Estate Agents in '.$location.' | '.$site_name;
                $meta->description = 'Looking for the latest property '.$type.' in '.$location.'? '.$site_name.' are your expert '.$location.' Estate Agents. All of your '.$location.' property needs can be found here in one convenient place!';
                $meta->image = themeAsset('images/logos/meta.jpg');

                if(!empty($object->meta)){
                    $page_meta = !empty($object->meta) ? (object)json_decode($object->meta) : false;
                    $meta->title = !empty($page_meta->title) ? $page_meta->title : $meta->title;
                    $meta->description = !empty($page_meta->description) ? $page_meta->description : $meta->description;
                    $meta->keywords = !empty($page_meta->keywords) ? $page_meta->keywords : false;
                    $meta->image = !empty($object->photo) ? url('assets/storage/'.$object->photo) : $meta->image;
                }
                break;

                case 'about-us':
                    // Define metadata settings for your specific page
                    $meta->title = !empty($page_meta->title) ? $page_meta->title : $meta->title;
                    $meta->description = !empty($page_meta->description) ? $page_meta->description : $meta->description;
                    $meta->keywords = !empty($page_meta->keywords) ? $page_meta->keywords : false;
                    $meta->image = !empty($object->photo) ? url('/storage/pages/65d710c736700.jpg'.$object->photo) : $meta->image;
                    // Add any other metadata properties as needed
                    break;
            default:
                // Title: [PAGE NAME] | [COMPANY NAME]
                // Description: Find out more about [TITLE OF PAGE] with [COMPANY NAME], your leading [TARGET-LOCATION] Estate Agents. Contact us for more information.

                $target_location = settings('default_location', config('app.default_location'));
                $site_name = settings('site_name', config('app.name'));
                $meta->title = !empty($object->title) ? $object->title.' | '.settings('site_name', config('app.name')) : settings('site_name', config('app.name'));
                $meta->description = !empty($object->description) ? $object->description : "Find out more about {$object->title} with {$site_name}, your leading {$target_location} Estate Agents. Contact us for more information.";
                $meta->image = themeAsset('images/logos/meta.jpg');

                if(!empty($object->meta)){
                    $page_meta = !empty($object->meta) ? (object)json_decode($object->meta) : false;
                    $meta->title = !empty($page_meta->title) ? $page_meta->title : $meta->title;
                    $meta->description = !empty($page_meta->description) ? $page_meta->description : $meta->description;
                    $meta->keywords = !empty($page_meta->keywords) ? $page_meta->keywords : false;
                    $meta->image = !empty($object->photo) ? url('assets/storage/'.$object->photo) : $meta->image;
                }
                break;
        }

        return $meta;

    }

}

function invalidFeilds($rules, $validator)
{
    $invalidFeilds = [];

    $messages = $validator->messages();
    foreach ($rules as $feild_name => $feild_val) {
        if($messages->has($feild_name)){
            $invalidFeilds[] = $feild_name;
        }
    }

    return $invalidFeilds;
}

/*---------------------------------------
* Google recaptcha API
---------------------------------------*/
// new googlerecaptcha
function googleRecaptchaV3($grecaptcharesponse, $ip=''){
    $post_data = http_build_query(
        array(
            'secret' => settings('recaptcha_private_key'),
            'response' => $grecaptcharesponse,
            'remoteip' => $ip
        )
    );
    $response = file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify',
        false,
        stream_context_create([
            'http' => [
                'method'  => 'POST',
                'timeout' => 5, // ✅ prevent hanging
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            ]
        ])
    );
    $GRresult = json_decode($response);
    return $GRresult;

    // $opts = array('http' =>
    //     array(
    //         'method'  => 'POST',
    //         'header'  => 'Content-type: application/x-www-form-urlencoded',
    //         'content' => $post_data
    //     )
    // );
    // $context  = stream_context_create($opts);
}

/*---------------------------------------
* PROPERTY IMAGE - Check if portrait
---------------------------------------*/
function is_portrait($image_src = '')
{
    $flag=false;

    if (! empty($image_src) && is_readable($image_src))
    {
        @list($width, $height) = getimagesize(($image_src));

        if( ($height > $width && $height != $width) || $width < 350 )
        {
            $flag = true;
        }
    }

    return $flag;
}

/*---------------------------------------
* Overide Metadata with URL
---------------------------------------*/
if (! function_exists('get_custom_metadata')) {
    function get_custom_metadata($url = false)
    {
        if(!$url) return false;

        $url = rtrim($url, '/');
        $url = str_replace('https://', '', $url);
        $url = str_replace('http://', '', $url);
        $url = str_replace('www.', '', $url);

        $result = Metadata::getMetadata($url);
        //$metadata = $result ? Metadata::getMetadata($url)->first() : false;

        return $result;
    }
}

/*---------------------------------------
* Check if search page
---------------------------------------*/
if (! function_exists('is_search_page')) {
    function is_search_page()
    {
        $flag = false;
        $controller = Route::getCurrentRoute()->getActionName();
        if( $controller == 'App\Http\Controllers\Frontend\PropertiesController@index' ){
            $flag = true;
        }
        return $flag;
    }
}

/*-------------------------------------
* User Stuff
------------------------------------- */

if(! function_exists('get_name'))
{
    function get_name($part)
    {
        if(Auth::check())
        {
            $name = explode(' ', Auth::user()->name, 2);

            if(is_array($name))
            {
                $firstname = $name[0];

                if(isset($name[1]))
                {
                    $surname = $name[1];
                }

                if($part == 'firstname')
                {
                    return $firstname;
                }

                if($part == 'surname')
                {
                    return $surname;
                }
            }

            return Auth::user()->name;
        }
    }
}

/*-------------------------------------------------------------
*
* ** CONFIGS
*
--------------------------------------------------------------*/

if (! function_exists('min_price')) {
    function min_price()
    {
        return 0;
    }
}

if (! function_exists('max_price')) {
    function max_price()
    {
        return 500000000;
    }
}

if (! function_exists('sale_price'))
{
    function sale_price($max=true)
    {
        $minPrice = config('custom.sale_price');

        $data = [];
        foreach($minPrice as $num)
        {
            $numDisplay = pw_price_format($num);
            if($max && $num == 5000000)
            {
                $data[200000000] = settings('currency_symbol').($numDisplay).'+';
            }else{
                $data[$num] = settings('currency_symbol').($numDisplay);
            }

        }

        return $data;
    }
}

if (! function_exists('rent_price'))
{
    function rent_price()
    {
        $maxPrice = config('custom.rent_price');

        $data = [];
        foreach($maxPrice as $num)
        {
            $data[$num] = settings('currency_symbol').number_format($num);
        }

        return $data;
    }
}


/*-------------------------------------
* Time / Date Stuff
--------------------------------------*/

if (! function_exists('greet'))
{
    function greet()
    {
        $now = Carbon::now();

        $hour = $now->format('H');

        if ($hour < 12)
        {
            return 'Good morning';
        }
        if ($hour < 17)
        {
            return 'Good afternoon';
        }

        return 'Good evening';
    }
}

if (! function_exists('thousandscurrencyformat'))
{
    function thousandsCurrencyFormat($num) {

        if($num>1000) {

            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];

            return $x_display;

        }

        return $num;
    }

}

if (! function_exists('blankImg'))
{
    function blankImg($placeholder = false) {

        if(!empty($placeholder)){
            return $placeholder;
        }else{
            return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
        }
    }
}

if (!function_exists('base64LoadingSpinner'))
{
    function base64LoadingSpinner() {
        return 'data:image/gif;base64,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';
    }
}

if (! function_exists('pw_price_format')) {
    function pw_price_format($num=0,$delimeter=','){
        $numDisplay = 0;
        if(!empty($num)){
            $numDisplay = number_format($num);
            $numDisplay = str_replace(',',$delimeter,$numDisplay);
            $numDisplay = $numDisplay;
        }
        return $numDisplay;
    }
}


/*-------------------------------------------------------------
*
* ** URL for language
*
--------------------------------------------------------------*/
if (! function_exists('lang_url')) {
    function lang_url($slug)
    {
        if(settings('translations'))
        {
            $languages = Cache::remember('defaultLanguage', config('custom.cache.default_cache_time'), function ()
            {
                return Languages::first();
            });

            if($languages)
            {
                if(config('app.locale') !== $languages->language_default)
                {
                    return LaravelLocalization::localizeUrl($slug);
                }
                else
                {
                    return url($slug);
                }
            }
            else
            {
                return url($slug);
            }
        }
        else
        {
            return url($slug);
        }
    }

}

/**
 * Gets Default Language
 */

function default_language()
{
    if(settings('translations'))
    {
        $languages = Languages::first();

        if($languages)
        {
            $default = $languages->language_default;
        }
    }
    else
    {
        $default = 'en'; // EN - Can change if needed..
    }

    return $default;
}

/**
 * Makes translation fall back to specified value if definition does not exist
 *
 * @param string $key
 * @param null|string $fallback
 * @param null|string $locale
 * @param array|null $replace
 *
 * @return array|\Illuminate\Contracts\Translation\Translator|null|string
 */
function trans_fb(string $key, ?string $fallback = null, ?string $locale = null, ?array $replace = [])
{
    if (\Illuminate\Support\Facades\Lang::has($key, $locale))
    {
        return trans($key, $replace, $locale);
    }

    return $fallback;
}

if(! function_exists('split_name'))
{
    function split_name($name)
    {
        $parts = array();

        while ( strlen( trim($name)) > 0 )
        {
            $name = trim($name);
            $string = preg_replace('#.*\s([\w-]*)$#', '$1', $name);
            $parts[] = $string;
            $name = trim( preg_replace('#'.$string.'#', '', $name ) );
        }

        if (empty($parts))
        {
            return false;
        }

        $parts = array_reverse($parts);
        $name = array();
        $name['first_name'] = $parts[0];
        $name['middle_name'] = (isset($parts[2])) ? $parts[1] : '';
        $name['last_name'] = (isset($parts[2])) ? $parts[2] : ( isset($parts[1]) ? $parts[1] : '');

        return $name;
    }
}

if(! function_exists('pluralise'))
{
    function pluralise($quantity, $singular, $plural=null)
    {
        if ($quantity == 1 || !strlen($singular)) return $singular;
        if ($plural !== null) return $plural;

        $last_letter = strtolower($singular[strlen($singular) - 1]);
        switch ($last_letter)
        {
            case 'y':
                return substr($singular, 0, -1) . 'ies';
            case 's':
                return $singular . 'es';
            default:
                return $singular . 's';
        }
    }
}

//Get a Countries by Name
if (! function_exists('p_countries_name')) {
    function p_countries_name($default='Please select...'){

        $data = ['' => $default];
        $countries = country::orderBy('id')->get();
        foreach($countries as $country){
            $data[$country->name] = $country->name;
        }

        return $data;
    }
}

//Get a Countries by slug
if (! function_exists('p_countries')) {
    function p_countries($default='Please select...'){

        $data = ['' => $default];
        $countries = country::orderBy('id')->get();
        foreach($countries as $country){
            $data[$country->name] = $country->name;
        }

        return $data;
    }
}

//Get a Country by slug
if (! function_exists('display_country')) {
    function display_country($country_slug){

        $p_countries = p_countries();
        $country = !empty($p_countries[$country_slug]) ? $p_countries[$country_slug] : ucwords($country_slug);

        return $country;
    }
}

if (! function_exists('p_communities')) {
    function p_communities($default='Please select...', $byId=TRUE){
        $communities = Community::Published()->orderBy('sequence', 'ASC')->get();
        $data = ($default) ? ['' => $default] : [];
        foreach($communities as $community){
            if($byId){
                $data[$community->id] = $community->name;
            }else{
                $data[urlencode(strtolower($community->name))] = $community->name;
            }
        }
        return $data;
    }
}


/**
 * PHP Version of PMT in Excel.
 *
 * @param float $apr
 *   Interest rate.
 * @param integer $term
 *   Loan length in years.
 * @param float $loan
 *   The loan amount.
 *
 * @return float
 *   The monthly mortgage amount.
 */
function pmt($apr, $term, $loan) {
  $term = $term * 12;
  $apr = $apr / 1200;
  $amount = $apr * -$loan * pow((1 + $apr), $term) / (1 - pow((1 + $apr), $term));
  return $amount;
}


if(! function_exists('arrayToSentence'))
{
    function arrayToSentence($array,$type='property type')
    {
        $sentence = '';
        if( !empty($array) && is_array($array) && count($array) ){

            if($type=='property type'){
                $arrayTemp = [];

                foreach($array as $item){
                    $propertyType = propertyType::findBySlug($item);
                    if(!empty($propertyType->name)){
                        $arrayTemp[] = $propertyType->name;
                    }
                }
                $array = [];
                $array = $arrayTemp;
            }

            $sentence = implode(', ', $array);
            $sentence = urldecode($sentence);
            $sentence = ucwords($sentence);
        }
        return $sentence;
    }
}

if(! function_exists('remapPropertyTypes'))
{
    function remapPropertyTypes($propertyType)
    {
        $remaps = [
            'luxury-listing' => 'luxury',
            'residential-rent-properties' => 'residential-properties',
            //'residential-properties-land' => 'land',
            'commercial-rent-property' => 'commercial-property',
        ];

        $value = $propertyType;

        if(!empty($propertyType)){
            if( array_key_exists( $propertyType, $remaps) ) {
                $value = $remaps[$propertyType];
            }
        }

        return $value;
    }
}

/*** Currencies***************/
if(! function_exists('all_currencies'))
{
    function all_currencies()
    {
        $currencies = config('data.currencies');
        return $currencies;
    }
}

if(! function_exists('get_current_currency'))
{
    function get_current_currency()
    {
        $current_currency = session('current_currency');
        if(empty($current_currency)){
            Session::put('current_currency', 'AED');
            $current_currency  = 'AED';
        }
        return $current_currency;
    }
}


if (!function_exists('get_locations')) {
        function get_locations($ptype = null) {

            $data = ['' => 'LOCATION'];
            $locations = \App\Property::select('country')->where('status','>=', 0);

            if(!is_null($ptype) && $ptype == 'rent'){
                $locations->where('is_rental', 1);
            }

            $locations = $locations->orderBy('country', 'ASC')->groupBy('country')->get();

            foreach($locations as $location) {
                 $data[$location->country] = $location->country;
            }
            return $data;
        }
}


// if (!function_exists('get_areas')) {
//         function get_areas($default='AREA') {

//             $locations = \App\Property::select('town')->where('status','>=', 0)->orderBy('town', 'ASC')->groupBy('town')->get();
//             $data = ['' => $default];
//             foreach($locations as $location) {
//                 if($location->town)
//                 $data[$location->town] = $location->town;
//             }


  

//             return $data;
//         }
// }

if (!function_exists('get_areas')) {
    function get_areas($default='AREA', $country = null, $ptype = null) {

    $query = \App\Property::select('town')->where('status','>=', 0);
    
    // Filter by country if provided
    if ($country && $country !== 'all') {
        $query->where('country', $country);
    }

    if(!is_null($ptype) && $ptype == 'rent'){
        $query->where('is_rental', 1);   
    }
    
    $locations = $query->orderBy('town', 'ASC')->groupBy('town')->get();
            $data = ['' => $default];
            foreach($locations as $location) {
                if($location->town)
                $data[$location->town] = $location->town;
            }

        return $data;
    }
}

if (!function_exists('get_complexx')) {
    function get_complexx($default='PROJECT',$area = null, $ptype = null) {
        $data = ['' => $default];
        if($area !== null){
            $projects = \App\Property::select('complex_name')->where('status','>=', 1);
            
            if(!is_null($ptype) && $ptype == 'rent'){
                $projects->where('is_rental', 1);   
            }else{
                $projects->where('is_rental', 0);
            }
    
            $projects = $projects->orderBy('complex_name', 'ASC')->groupBy('complex_name');
            $complex = urldecode(trim($area));
            if($area && $area !== ''){
                $projects->where(function($projects) use ( $complex )
                {
                    $projects->orwhere([['street', 'LIKE', '%'.$complex.'%']]);
                    $projects->orwhere([['town', 'LIKE', '%'.$complex.'%']]);
                });
            }
            $projects = $projects->get();
            foreach($projects as $location) {
                if($location->complex_name)
                $data[$location->complex_name] = $location->complex_name;
            }
        }


        return $data;
    }
}

if(!function_exists('min_tel_length')){
    function min_tel_length(){
        $countryDigits = [
            '91' => 10,   // India
            '1'  => 10,   // USA/Canada
            '971' => 9,   // UAE
            '44' => 10,   // UK
            '61' => 9,    // Australia
            '49' => 10,   // Germany
        ];
        return $countryDigits;
    }
}

if(!function_exists('humanPrice')){
    function humanPrice($number)
    {
        if ($number >= 1_000_000_000) {
            return round($number / 1_000_000_000, 2) . 'B';
        }
        if ($number >= 1_000_000) {
            return round($number / 1_000_000, 2) . 'M';
        }
        if ($number >= 1_000) {
            return round($number / 1_000, 2) . 'K';
        }
        return $number;
    }
}

if(!function_exists('priceConverter')){
    function priceConverter($amount=null){
        return settings('currency_symbol');
    }
}
