<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Property;
use App\Enquiry;
use App\Subscriber;
use App\whatsapp_clicks;
use Illuminate\Support\Facades\DB;

use Auth;

class Dashboard extends Model
{
    protected $branches_option;
    protected $branch_id;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->branches_option = settings('branches_option');

        if($this->branches_option == '1')
        {
            $this->branch_id = Auth::user()->branch_id;
        }
    }

    function totalProperties()
    {
        $where = [];
        if(Auth::user()->role_id == '3')
        {
          $where[] = ['user_id',  Auth::user()->id];

        }
        $where[] = ['status', '!=', 1];
        return Property::withTrashed()->where($where)->orWhere('branch_id', $this->branch_id)->count();

    }

    function sevenDaysProperties()
    {
        $date_7days_before = date('Y-m-d H:i:s',strtotime('-7 days'));
        $date_now = date('Y-m-d H:i:s');
        $where[] = ['created_at', '>=', $date_7days_before];
        $where[] = ['created_at', '<=', $date_now];

        $query = Property::where($where);
        $count = $query->get()->count();

        return $count;
    }

    function propertiesHas3Enquiries()
    {
        return Property::has('enquiries', '>=', 3)->get()->count();
    }

    function totalActiveProperties(){

        $where = [];
        if(Auth::user()->role_id == '3')
        {
          $where[] = ['user_id',  Auth::user()->id];
        }
        $where[] = ['status', 0];
        return  Property::where($where)->orWhere('branch_id', $this->branch_id)->count();

    }

    function totalInActiveProperties(){
        return Property::where(['status' => -1])->get()->count();
    }

    function totalSubscriber(){
        return Subscriber::get()->count();
    }

    function totalwhatsappclicks(){
        return whatsapp_clicks::get()->count();
    }


    function sevenDaysSubscriber(){
        $date_7days_before = date('Y-m-d H:i:s',strtotime('-7 days'));
        $date_now = date('Y-m-d H:i:s');
        $where[] = ['created_at', '>=', $date_7days_before];
        $where[] = ['created_at', '<=', $date_now];

        $query = Subscriber::where($where);
        $count = $query->get()->count();

        return $count;
    }

    function totalEnquiries(){
        return Enquiry::get()->count();
    }

    function sevenDaysEnquiries(){

        $date_7days_before = date('Y-m-d H:i:s',strtotime('-7 days'));
        $date_now = date('Y-m-d H:i:s');
        $where[] = ['created_at', '>=', $date_7days_before];
        $where[] = ['created_at', '<=', $date_now];

        $query = Enquiry::where($where);

        if( (Auth::user()->role_id == '3')){
          $query->whereHas('property', function($query)
          {
              $query->whereUserId(Auth::user()->id);
          });
        }

        $count = $query->get()->count();

        return $count;
    }

    function thirtyDaysEnquiries(){
        $date_days_before = date('Y-m-d H:i:s',strtotime('-30 days'));
        $date_now = date('Y-m-d H:i:s');
        $where[] = ['created_at', '>=', $date_days_before];
        $where[] = ['created_at', '<=', $date_now];

        $query = Enquiry::where($where);

        if( (Auth::user()->role_id == '3')){
          $query->whereHas('property', function($query){
              $query->whereUserId(Auth::user()->id);
          });
        }

        $count = $query->get()->count();

        return $count;
    }


}
