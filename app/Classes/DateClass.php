<?php
namespace App\Classes;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DateClass
{
    public static function get_date_range()
    {
        $start = Carbon::now()->addDay(1);
        $end = Carbon::now()->addWeeks(2);

        $carbon_dates = CarbonPeriod::create($start, $end);

        $dates = array();

        foreach($carbon_dates as $date)
        {
            $dates[] = array
            (
                'day'           => $date->format('D'),
                'date'          => $date->format('d'),
                'date_friendly' => $date->format('l jS F Y')
            );
        }

        return $dates;
    }

    public static function get_months()
    {
        $start = Carbon::now()->addDay(1);
        $end = Carbon::now()->addWeeks(2);

        $start_month = $start->format('F'); //$start->monthName;
        $end_month = $end->format('F'); //$end->monthName;

        return array($start_month, $end_month);
    }
}
