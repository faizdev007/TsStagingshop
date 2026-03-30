<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SaveSearch extends Model
{
    protected $table = 'saved_searches';
    protected $primaryKey = 'saved_search_id';

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function getSearchHeadlineAttribute()
    {
        $criteria = json_decode($this->saved_search_criteria);

        $items = [];
        if (!empty($criteria->beds)) $items[] = $criteria->beds.' Bed';
        /*
        if(!empty($criteria->property_type))
        {
            $items[] = Str::plural($criteria->property_type);
        }
        else
        {
            $items[] = "Properties";
        }
        */

        $items[] = "Properties";

        if(!empty($criteria->price_range))
        {
            $items[] = 'priced : '. settings('currency_symbol'). $criteria->price_range;
        }

        $items[] = ($criteria->for) ? 'For Sale' : 'To Rent';

        if(!empty($criteria->in))
        {
            $items[] = 'In ' . $criteria->in;
        }

        if(!empty($criteria->property_type))
        {
            $items[] = arrayToSentence($criteria->property_type, 'property type');
        }

        $value = implode(' ', $items);

        return $value;
    }


}
