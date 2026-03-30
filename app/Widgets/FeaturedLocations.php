<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;

class FeaturedLocations extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        //
        $themeDirectory = themeOptions();

        return view('frontend.'.$themeDirectory.'.widgets.featured_locations', [
            'config' => $this->config,
        ]);
    }
}
