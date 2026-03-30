<?php

namespace App\Http\Controllers\Frontend;

use App\Models\SaveSearch;
use App\PropertyAlert;
use App\PropertyType;
use App\Shortlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MembersController extends Controller
{
    private $is_set;
    private $user_id;

    public function __construct()
    {
        $this->is_set = settings('members_area');

        //Prevent Access if Turned Of....
        if($this->is_set == '0' || !$this->is_set)
        {
            return redirect('/')->send();
        }

        $this->middleware(function ($request, $next)
        {
            if(Auth::user())
            {
                // Redirect to admin if not a member
                if( Auth::user()->role_id != '4'){
                    return redirect('admin');
                }else{
                    $this->user_id = Auth::user()->id;
                    return $next($request);
                }
            }
            else
            {
                return redirect('login');
            }
        });
    }

    public function shortlist()
    {
        $shortlist = Shortlist::where('user_id', $this->user_id)->with('property')->orderBy('created_at', 'desc')->paginate(12);

        $view = 'frontend.demo1.account.shortlist';

        // Change View Per Template...
        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view,  ['shortlist' => $shortlist]);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.account.shortlist', ['shortlist' => $shortlist]);
        }
    }

    public function saved_search()
    {
        $view_data = array
        (
            'searches'  => SaveSearch::where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get()
        );

        $view = 'frontend.demo1.account.saved-search';

        // Change View Per Template...
        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view,  $view_data);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.account.saved-search', $view_data);
        }
    }

    public function convert_to_alert(Request $request, $id)
    {
        $SaveSearch = SaveSearch::find($id);

        if($SaveSearch)
        {
            $search_criteria = json_decode($SaveSearch->saved_search_criteria);

            $for = (!empty($search_criteria->for) && $search_criteria->for == 'rent') ? 1 : 0;
            $in = (!empty($search_criteria->in) ) ? $search_criteria->in : '';

            /*
            $price_from = 0;
            $price_to = 0;
            if(!empty($search_criteria->price_range) )
            {
                $price_range_array = explode('-',$search_criteria->price_range);
                $price_from = !empty($price_range_array[0]) ? $price_range_array[0] : 0;
                $price_to = !empty($price_range_array[1]) ? $price_range_array[1] : 0;
            }
            */

            $price_from = !empty($search_criteria->min_price) ? $search_criteria->min_price : '';
            $price_to = !empty($search_criteria->max_price) ? $search_criteria->max_price : '';

            $area_from = 0;
            $area_to = 0;

            if(!empty($search_criteria->area) )
            {
                $area_array = explode('-',$search_criteria->area);
                $area_from = !empty($area_array[0]) ? $area_array[0] :0;
                $area_to = !empty($area_array[1]) ? $area_array[1] : 0;
            }

            if(!empty($search_criteria->beds))
            {
                $beds = $search_criteria->beds;
            }
            else
            {
                $beds = NULL;
            }

            //$propertyType = PropertyType::where('slug', $search_criteria->property_type)->first();
            //$property_type_id = ($propertyType) ? $propertyType->id :  NULL;

            $property_type_ids = '';

            if(!empty($search_criteria->property_type)){
                $property_type_array = $search_criteria->property_type;
                $property_type_id_array = [];
                if( !empty($property_type_array) && is_array($property_type_array) && count($property_type_array) ){
                    foreach($property_type_array as $slug){
                        $property_type_id_array[] = get_ptype_id_by_slug($slug);
                    }
                    $property_type_ids = implode(',',$property_type_id_array);
                }
            }


            $PropertyAlert = new PropertyAlert;
            $PropertyAlert->fullname = Auth::user()->name;
            $PropertyAlert->email = Auth::user()->email;
            $PropertyAlert->contact = Auth::user()->telephone;
            $PropertyAlert->is_rental = $for;
            $PropertyAlert->in = $in;
            //$PropertyAlert->property_type_id = $property_type_id;
            $PropertyAlert->property_type_ids = $property_type_ids;
            $PropertyAlert->beds = $beds;
            if(!empty($search_criteria->min_price)){
                $PropertyAlert->price_from = $search_criteria->min_price;
            }
            if(!empty($search_criteria->max_price)){
                $PropertyAlert->price_to = $search_criteria->max_price;
            }
            $PropertyAlert->is_active = 1;
            $PropertyAlert->user_id = Auth::user()->id;

            $PropertyAlert->save();

            // Update Saved Search to say it's converted...
            $saved_search = SaveSearch::find($id);
            $saved_search->is_converted = 'y';
            $saved_search->save();

            // Redirect to Property Alerts - Notify It's Done...
            $request->session()->flash('message_success', 'The selected saved search has now been also converted into a Property Alert. You will receive property updates when properties matching this criteria is added onto the website');

            return redirect('account/property-alerts');

        }
        else
        {
            return redirect(url('account/saved-searches'));
        }
    }

    public function property_alerts()
    {
        $view_data = array
        (
            'alerts'            => PropertyAlert::where('user_id', $this->user_id)->orderBy('created_at', 'desc')->get(),
            'propertyTypes'     => propertyType::orderBy('name')->get()
        );

        $view = 'frontend.demo1.account.property-alerts';

        // Change View Per Template...
        if(view()->exists($view))
        {
            // Shared View in the Templates...
            return view($view,  $view_data);
        }
        else
        {
            // Load Shared View...
            return view('frontend.shared.account.property-alerts', $view_data);
        }
    }

}
