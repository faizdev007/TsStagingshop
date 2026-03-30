<?php

namespace App;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Config;

class Enquiry extends Model
{
    use GeneratesUuid;

    /**
     * Enquiries table
     */
    protected $table = 'leads';
    protected $primaryKey = 'id';

    protected $appends = ['friendly_date'];

    protected $fillable = ['ref'];





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

    public function scopeGetWhereEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    /* --------------------------------------
    * Custom queries
    ----------------------------------------*/
    public function total_past_30days_by_property($property_ref)
    {
        // Enquiries from last 30 days
        $date_7days_before = date('Y-m-d H:i:s',strtotime('-30 days'));
        $date_now = date('Y-m-d H:i:s');

        $where = [];
        $where[] = ['created_at', '>=', $date_7days_before];
        $where[] = ['created_at', '<=', $date_now];
        $where[] = ['ref', $property_ref];

        $total =  Enquiry::where($where)->count();

        return $total;
    }

    /*
    * get property of a lead
    */
    public function property()
    {
        return $this->belongsTo('App\Property', 'ref', 'ref');
    }

    public function search($request, $limit=10)
    {
        $is_agent = Auth::user()->role_id == '3';
        $is_branch = false;

        $branch_enabled = settings('branches_option');

        /* //Might need Branch Manager
        if($branch_enabled == '1' && $is_agent == true)
        {
            $is_branch = true;
        }
        */


        $enquiries = Enquiry::orderBy('created_at', 'DESC')

            // Filter for agent USERS
            ->when($is_agent, function($query) use ($request, $is_branch)
            {
                // If Branch is Set - Show ALL enquiries for that Branch...
                if($is_branch == true)
                {
                    return false;
                }
                else
                {
                    $query->whereHas('property', function($query){
                        $query->whereUserId(Auth::user()->id);
                    });
                }
            })

            /* //Might need Branch Manager
            // If Branch is Set - Show ALL enquiries for that Branch...
            ->when($is_branch, function($query) use ($request)
            {
                $query->where('branch_id', Auth::user()->branch_id);
            })
            */

            // Filter Status
            /*
            ->when(1, function($query) use ($request){
                // Default Active
                if($request->input('status') == 'active' || $request->input('status') == ''){
                    return $query->where('archived_at', NULL);
                }

                if($request->input('status') == 'archives'){
                    return $query->where('archived_at', '!=', NULL);
                }
            })
            */

            // filter categories
            ->when($request->input('category'), function($query) use ($request)
            {
                if(settings('new_developments') == '1')
                {
                    $categories = Config::get('data.categories_developments');
                }
                else
                {
                    $categories = Config::get('data.categories');
                }

                //return $query->where('category', $categories[$request->input('category')]);
                $cat = !empty($categories[$request->input('category')]) ? $categories[$request->input('category')] : $request->input('category');
                return $query->where('category', 'like', "%{$cat}%");
            })

            ->when($request->input('date_from'), function($query) use ($request){
                return $query->where(function ($query) use ($request) {
                    $query->where('created_at', '>=', $request->input('date_from').' 00:00:00');
                });
            })

            ->when($request->input('date_to'), function($query) use ($request){
                return $query->where(function ($query) use ($request) {
                    $query->where('created_at', '<=', $request->input('date_to').' 23:59:59');
                });
            })

            // filter other data
            ->when($request->input('q'), function($query) use ($request){
                return $query->where(function ($query) use ($request) {
                        $query
                            ->where('ref', 'like', '%'.$request->input('q').'%')
                            ->orwhere('name', 'like', '%'.$request->input('q').'%')
                            ->orWhere('email', 'like', '%'.$request->input('q').'%')
                            ->orWhere('telephone', 'like', '%'.$request->input('q').'%')
                            ->orWhere('message', 'like', '%'.$request->input('q').'%')
                            ->orWhere('reply_message', 'like', '%'.$request->input('q').'%');
                    });
            })

            // filter Enquiries from last 7 days
            ->when($request->input('sevenDays'), function($query) use ($request){
                return $query->where(function ($query) use ($request) {
                    $date_7days_before = date('Y-m-d H:i:s',strtotime('-7 days'));
                    $date_now = date('Y-m-d H:i:s');
                    $query->where('created_at', '>=', $date_7days_before)
                          ->Where('created_at', '<=', $date_now);
                    });
            })

            // filter Enquiries from last 30 days
            ->when($request->input('thirtyDays'), function($query) use ($request){
                return $query->where(function ($query) use ($request) {
                    $date_7days_before = date('Y-m-d H:i:s',strtotime('-30 days'));
                    $date_now = date('Y-m-d H:i:s');
                    $query->where('created_at', '>=', $date_7days_before)
                          ->Where('created_at', '<=', $date_now);
                    });
            })

            ->where('category', '!=', 'Automated') // Ignore Automated Leads (Enquiries)...

            ->paginate($limit);


        return $enquiries;

    }

    function getFriendlyDateAttribute()
    {
        return date("jS F Y", strtotime($this->created_at));
    }

    public function lead_emails()
    {
        return $this->hasOne('App\Models\LeadAutomation', 'lead_id', 'id')->where('lead_contact_type', 'email');
    }

    public function branch()
    {
        return $this->hasOne('App\Models\Branch', 'branch_id', 'branch_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'user_id', 'id');
    }

}
