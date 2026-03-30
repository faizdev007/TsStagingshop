<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Testimonial;

class Testimonials extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */

    protected $config = [
        'class' => ''
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $themeDirectory = themeOptions();

        $testimonials = Testimonial::orderBy('priority', 'ASC')->limit(7)->get();

        return view('frontend.'.$themeDirectory.'.widgets.testimonials', [
            'testimonials' => $testimonials,
            'config' => $this->config
        ]);
    }
}
