<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;

trait CountryTrait
{
    function get_iso_name($country)
    {
        $data = @file_get_contents('http://country.io/names.json');
        $countries = json_decode($data);

        if($countries)
        {
            foreach($countries as $k => $v)
            {
                if($v == $country)
                {
                    return $k;
                }
            }
        }
    }

    function get_currency_code($country)
    {
        $data = @file_get_contents('http://country.io/currency.json');
        $countries = json_decode($data);

        if($countries)
        {
            foreach($countries as $k => $v)
            {
                if($k == $country)
                {
                    return $v;
                }
            }
        }
    }

    function get_phone_code($country)
    {
        $data = @file_get_contents('http://country.io/phone.json');
        $countries = json_decode($data);

        if($countries)
        {
            foreach($countries as $k => $v)
            {
                if($k == $country)
                {
                    return $v;
                }
            }
        }
    }

    function get_currency_conversions($amount, $currency_to_convert = 'USD')
    {
        $currency_rates = Storage::get('currency-rates.json');

        $data = json_decode($currency_rates);

        $exchange_rate = $data->rates->{$currency_to_convert};

        return number_format($amount * $exchange_rate, 0);
    }

}
