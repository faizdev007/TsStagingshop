<?php

namespace App\Traits;
use App\Property;
use Illuminate\Support\Str;

trait HelperTrait
{

    /*
    * Get taxable sum
    */
    function get_taxable_sum($criteria = array(), $price){

        $tax_sum_data = array();

        foreach($criteria as $criteria_str){

            $criteria_data = explode('|', $criteria_str);

            // get data from criteria
            $percent = (float)$criteria_data[0];
            $range = $criteria_data[1];

            // get the range data
            $range_data = array_map('intval', explode('-', $range));

            // deduct the min range
            $deducted_price = $price - min($range_data);
            $limit = max($range_data);

            // get the set percentage
            $percentStr = str_replace('percent-', '', $percent);
            $percentage = $percentStr / 100;

            // if deducted price is bigger than the limit then move up

            // minimum
            if(0 == min($range_data) && $percent == 0){

                $tax_sum_data[$percent] = 0;

            // get the max deduction
            }elseif($price > max($range_data)){

                $tax_sum_data[$percent] = max($range_data) - min($range_data);

            // get the maximum
            }elseif(9999999999 == max($range_data) && $price > min($range_data)){

                $tax_sum_data[$percent] = $price - min($range_data);

            // get the deducted by minimum
            }else{

                $taxable = ($deducted_price < 1) ? 0 : $deducted_price;
                $tax_sum_data[$percent] = ($taxable > 0) ? $taxable : 0;

            }
        }
        return $tax_sum_data;
    }

}
