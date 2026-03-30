<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Property;

class LatestProperties extends AbstractWidget
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
        $themeDirectory = themeOptions();
        // Get the four latest properties using a query scope
        // Surely they want the nice, new, ExpertAgent properties? (Hence, feed ID 3; Had been feed ID 1 = Propertybase.) - RH
        $properties = Property::latest()->where('status', 0)->take(12)->get();

        return view('frontend.'.$themeDirectory.'.widgets.latest_properties', [
            'config' => $this->config,
            'properties' => $properties,
        ]);
    }
}
