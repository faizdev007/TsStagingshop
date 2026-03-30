<?php

namespace App\Widgets;

use App\Property;
use Arrilot\Widgets\AbstractWidget;

class NewDevelopments extends AbstractWidget
{
    protected $config = [];

    public function run()
    {
        $themeDirectory = themeOptions();

        $property = new Property();
        $developments = $property->whereHas('development')->limit(6)->get();

        return view('frontend.'.$themeDirectory.'.widgets.featured_development', [
            'config' => $this->config,
            'properties' => $developments
        ]);
    }
}
