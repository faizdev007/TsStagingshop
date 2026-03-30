<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\PropertyType;

class PropertyAlert extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'template' => 1
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */

     public function run()
     {
        $themeDirectory = themeOptions();
         $propertyTypes = propertyType::orderBy('name')->get();

         return view('frontend.'.$themeDirectory.'.widgets.property_alert', [
             'config' => $this->config,
             'propertyTypes' => $propertyTypes,
         ]);

     }

}
