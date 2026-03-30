<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Property;

class FeaturedProperties extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'template' => 2,
        'title' => 'Sir Paul’s Picks of the Month',
    ];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $themeDirectory = themeOptions();

        $baseQuery = Property::query()
        ->whereNotIn('status', [-1,1,16,17])
        ->select([
            'id','name','status','description','street','town','complex_name',
            'region','postcode','country','latitude','longitude',
            'beds','baths','ref','price','price_qualifier'
        ]);

        // Latest featured
        $updatedProperties = (clone $baseQuery)
            ->where('is_featured', 1)
            ->latest('updated_at')
            ->limit(6)
            ->get();

        // Random featured (excluding above)
        $randomProperties = (clone $baseQuery)
            ->where('is_featured', 1)
            ->whereNotIn('id', $updatedProperties->pluck('id'))
            ->inRandomOrder()
            ->limit(12)
            ->get();

        $properties = $updatedProperties->merge($randomProperties);

        // Fill remaining if less than 18
        if ($properties->count() < 18) {
            $additional = (clone $baseQuery)
                ->whereNotIn('id', $properties->pluck('id'))
                ->inRandomOrder()
                ->limit(18 - $properties->count())
                ->get();

            $properties = $properties->merge($additional);
        }

        // Final shuffle (optional)
        $properties = $properties->shuffle();

        return view('frontend.'.$themeDirectory.'.widgets.featured_properties', [
            'config' => $this->config,
            'properties' => $properties
        ]);
    }
}