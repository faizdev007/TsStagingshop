<?php

namespace App\Models;

use App\Property;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PropertyClaim extends Model
{
    protected $fillable = [
        'property_id',
        'user_id',
        'property_status',
        'property_value',
        'property_claim_approved',
        'property_is_rental',
        'property_provide_date',
    ];

    protected $table = 'property_claims';

    protected $casts = [
        'property_provide_date' => 'date',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPriceNumeric(): bool
    {
        return is_numeric($this->property_value);
    }

    public function getConvertValueAttribute()
    {
        $value = $this->property_value ?? $this->property->price;
        
        if(Auth::check()) {
            $preferences = json_decode(Auth::user()->preferences);
        }

        $preference_currency = $preferences->currency ?? session()->get('current_currency') ?? settings('currency_symbol');

        $propertyCurrencies = all_currencies();
        $conversion = json_decode(file_get_contents(storage_path('app/public/currency-rates.json')));

        $converted = (float) $value * $conversion->rates->$preference_currency;

        $rent_freq = '';

        if (!empty($this->property->is_rental)) {
            switch ($this->property->rent_period) {
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

        return $propertyCurrencies[$preference_currency] . ' ' . number_format($converted,0) . $rent_freq;
    }

    static function gettotalvalue($claim){
        $totalvalue = 0;
        if($claim->count() > 0){
            foreach($claim->get() as $single){
                $totalvalue += isset($single->property_status) ? 0 : (isset($single->property_value) ?  $single->property_value : ($single->property->price));   
            }

            if(Auth::check()) {
                $preferences = json_decode(Auth::user()->preferences);
            }

            $preference_currency = $preferences->currency ?? session()->get('current_currency') ?? settings('currency_symbol');

            $propertyCurrencies = all_currencies();
            $conversion = json_decode(file_get_contents(storage_path('app/public/currency-rates.json')));

            $converted = (float) $totalvalue * $conversion->rates->$preference_currency;

            return $propertyCurrencies[$preference_currency] . ' ' . humanPrice($converted);
        }
        return $totalvalue;
    }

    static function gettotalactualvalue($claim){
        $totalvalue = 0;
        $valuearray = [];
        if($claim->count() > 0){
            foreach($claim->get() as $single){
                $totalvalue += $single->property->price ?? 0; 
                $valuearray[$single->property->id] = $single->property->price ?? 0;  
            }

            if(Auth::check()) {
                $preferences = json_decode(Auth::user()->preferences);
            }

            $preference_currency = $preferences->currency ?? session()->get('current_currency') ?? settings('currency_symbol');

            $propertyCurrencies = all_currencies();
            $conversion = json_decode(file_get_contents(storage_path('app/public/currency-rates.json')));

            $converted = (float) $totalvalue * $conversion->rates->$preference_currency;

            return $propertyCurrencies[$preference_currency] . ' ' . humanPrice($converted);
        }
        return $totalvalue;
    }

}