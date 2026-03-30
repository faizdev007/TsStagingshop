<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Development extends Model
{
    use SoftDeletes;

    protected $table = 'developments';
    protected $primaryKey = 'development_id';

    protected $fillable = ['development_title'];

    public function property()
    {
        return $this->belongsTo('App\Property', 'property_id', 'id');
    }

    public function units()
    {
        return $this->hasMany('App\Models\DevelopmentUnit', 'development_id', 'development_id')->orderBy('order');
    }

    public function getFromPriceAttribute()
    {
        $currency_symbol = settings('currency_symbol');
        $currency_symbol = (!empty($currency_symbol)) ? $currency_symbol : '&pound;';

        if(settings('price_format') == '1')
        {
            $from_price = pw_price_format($this->development_price_from, '.');
        }
        else
        {
            $from_price = number_format($this->development_price_from);
        }

        $value = sprintf('%s%s',
            settings('currency_symbol'),
            $from_price
        );

        return $value;
    }

    public function getDisplayPriceAttribute()
    {
        $currency_symbol = settings('currency_symbol');
        $currency_symbol = (!empty($currency_symbol)) ? $currency_symbol : '&pound;';

        if(settings('price_format') == '1')
        {
            $from_price = pw_price_format($this->development_price_from,'.');
            $to_price = pw_price_format($this->development_price_to,'.');
        }
        else
        {
            $from_price = number_format($this->development_price_from);
            $to_price = number_format($this->development_price_to);
        }

        $value = sprintf('%s%s - %s%s',
            settings('currency_symbol'),
            $from_price,
            settings('currency_symbol'),
            $to_price
        );

        return $value;
    }

    public function getDevelopmentAttributesAttribute()
    {
        $units = $this->units()->with('property_type')->get();

        $bedrooms = $units->pluck('development_unit_bedrooms')->toArray();
        $bedrooms = array_unique($bedrooms);
        sort($bedrooms);
        $bedrooms = implode("-", $bedrooms);
        $bedrooms .= " Beds";
        $property_types = $units->pluck('property_type.name')->toArray();

        if(isset($bedrooms))
        {
            $string = $bedrooms .' | ';
        }
        else
        {
            $string = '';
        }

        foreach($property_types as $property_type)
        {
            $string .= pluralise('',$property_type) .' | ';
        }

        return rtrim($string, ' |');
    }

    public function getPropertyTypesAttribute()
    {
        $units = $this->units()->with('property_type')->get();

        $property_types = $units->pluck('property_type.name')->toArray();

        $string = '';

        foreach($property_types as $property_type)
        {
            $string .= pluralise('',$property_type) .',';
        }

        return rtrim($string, ',');
    }
}
