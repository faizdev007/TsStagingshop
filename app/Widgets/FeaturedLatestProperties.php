<?php

namespace App\Widgets;

use App\Property;
use Arrilot\Widgets\AbstractWidget;

class FeaturedLatestProperties extends AbstractWidget
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

        // Get All Featured Property Types...
        $properties = Property::with('type')
            ->where([ ['status', 0], ['is_featured',1] ])
            ->get();

        if(!count($properties))
        {
            $properties = Property::whereHas('type')
                ->where('status', 0)
                ->orderByRaw("RAND()")
                ->limit(3)
                ->get();
        }

        return view('frontend.'.$themeDirectory.'.widgets.properties.featured', [
            'config' => $this->config,
            'properties' => $properties->groupBy('type.name')
        ]);
    }
}
