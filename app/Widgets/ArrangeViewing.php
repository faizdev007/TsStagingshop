<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Classes\DateClass;

class ArrangeViewing extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [

    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        return view('frontend.shared.viewing.viewing', [
            'dates'     => DateClass::get_date_range(),
            'months'    => DateClass::get_months(),
            'times'     => config('times.times')
        ]);
    }
}
