<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Property;

class PropertyStats extends Model
{
    // Table name
    protected $table = 'property_stats';

    // Primary key
    public $primaryKey = 'id';

    //Timestamps
    public $timestamps = false;

    /* --------------------------------------
    * Custom queries
    ----------------------------------------*/
    public function property_view_store($property_id)
    {
        // Retrieve the property by its ID
    $property = Property::find($property_id);

    // Check if the property exists
    if ($property) {
        $this->type = 'property_view';
        $this->property_id = $property_id;
        // $this->p_name = $property->name; // Store the property name
        $this->save();
    }
    }

    public function get_search_stats($property_id, $type='property_view')
    {
            $where = [];
            $where[] = ['type', $type];
            $where[] = ['property_id', $property_id];

            $stats  = PropertyStats::select('data', DB::raw('count(*) as total'))
                     ->where($where)
                     ->groupBy('data')
                     ->get();

            return $stats;
    }

    public function get_search_stats_30days_by_property($property_id, $type='property_view')
    {
            $date_7days_before = date('Y-m-d H:i:s',strtotime('-30 days'));
            $date_now = date('Y-m-d H:i:s');

            $where = [];
            $where[] = ['type', $type];
            $where[] = ['property_id', $property_id];
            $where[] = ['created_at', '>=', $date_7days_before];
            $where[] = ['created_at', '<=', $date_now];

            $stats  = PropertyStats::select('data', DB::raw('count(*) as total'))
                     ->where($where)
                     ->groupBy('data')
                     ->get();

            return $stats;
    }

    public function total($property_id, $type='property_view')
    {
        $where = [];
        $where[] = ['type', $type];
        $where[] = ['property_id', $property_id];
        $total =  PropertyStats::where($where)->count();
        return $total;
    }

    public function total_past_30days_by_property($property_id, $type='property_view')
    {
        //from last 30 days
        $date_7days_before = date('Y-m-d H:i:s',strtotime('-30 days'));
        $date_now = date('Y-m-d H:i:s');

        $where = [];
        $where[] = ['type', $type];
        $where[] = ['created_at', '>=', $date_7days_before];
        $where[] = ['created_at', '<=', $date_now];
        $where[] = ['property_id', $property_id];

        $total =  PropertyStats::where($where)->count();

        return $total;
    }

    public function property_search_store($properties, $url)
    {
        if(count($properties)){
            //$path = request()->path();
            $path = request()->getPathInfo().(request()->getQueryString() ? ('?' . request()->getQueryString()) : '');
            foreach($properties as $property){
                $PropertyStats = new PropertyStats;
                $PropertyStats->type = 'property_search';
                $PropertyStats->property_id = $property->id;
                // $PropertyStats->p_name = $property->name;
                $PropertyStats->data = $path;
                $PropertyStats->save();
            }
        }
    }

    public function property()
    {
        return $this->hasOne('App\Property', 'id', 'property_id');
    }

}
