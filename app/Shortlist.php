<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Request;
use Illuminate\Support\Facades\Auth;

class Shortlist extends Model
{
    // Table name
    protected $table = 'shortlists';

    // Primary key
    public $primaryKey = 'id';

    //Timestamps
    public $timestamps = false;

    /* --------------------------------------
    * Database relationship
    ----------------------------------------*/

    public function property()
    {
        return $this->hasOne('App\Property', 'id', 'property_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }


    /* --------------------------------------
    * Custom queries
    ----------------------------------------*/

    // Check if Shortlist exist
    public function check_exist($property_id, $user_id = NULL)
    {
        $ip = request()->ip();
        $where = [];

        if(Auth::user() && $user_id == NULL)
        {
            $user_id = Auth::user()->id;
        }

        $where[] = ['property_id', $property_id];
        if(settings('members_area'))
        {
            $where[] = ['user_id', $user_id]; // By users
        }else{
            $where[] = ['ip', $ip]; // non-members by IP
        }
        $check_exist =  Shortlist::where($where)->count();

        return $check_exist;
    }

    // Add Shortlist and return total
    public function add($property_id, $user_id = NULL)
    {
        $ip = request()->ip();
        if(Auth::user() && $user_id == NULL)
        {
            $user_id = Auth::user()->id;
        }

        $this->property_id = $property_id;
        if(settings('members_area'))
        {
            $this->user_id = $user_id;
        }
        $this->ip = $ip; // IP

        if(!$this->check_exist($property_id, $user_id)){
            $this->save();
            $total = $this->total();
            return $total;
        }else{
            return FALSE;
        }

    }

    // Remove Shortlist and return total
    public function remove($property_id, $user_id= NULL)
    {
        $ip = request()->ip();
        if(Auth::user() && $user_id == NULL)
        {
            $user_id = Auth::user()->id;
        }

        $where = [];

        $where[] = ['property_id', $property_id];
        if(settings('members_area'))
        {
            $where[] = ['user_id', $user_id];
        }else{
            $where[] = ['ip', $ip]; // non-members by IP
        }
        $shortlist = Shortlist::where($where);
        $shortlist->delete();

        $total = $this->total();
        return $total;
    }

    // Total Shortlist
    public function total($user_id = NULL)
    {
        $ip = request()->ip();
        if(Auth::user() && $user_id == NULL)
        {
            $user_id = Auth::user()->id;
        }

        $where = [];
        if(settings('members_area'))
        {
            $where[] = ['user_id', $user_id];
        }else{
            $where[] = ['ip', $ip]; // non-members by IP
        }
        $total =  Shortlist::where($where)->whereHas('property')->count();

        return $total;
    }

}
