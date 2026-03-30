<?php
namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Property;

class SimilarProperties extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [
        'template' => 1,
        'property' => true
    ];

    /**
     * Find similar properties with improved matching logic
     * 
     * @param Property $property
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function findSimilarProperties($property, $limit = 3)
    {
        $propertyModel = new Property();
        $similar_properties = collect([]);

        /*
        |--------------------------------------------------------------------------
        | Step 0: Identify property type & price usability
        |--------------------------------------------------------------------------
        | - Rent properties should NOT exceed the current rent price
        | - Sale properties can use a flexible price range
        */
        $is_rent_property = (int) $property->is_rental === 1;

        // Price is considered invalid if non-numeric or zero (Price on Application)
        $is_price_on_application = !is_numeric($property->price) || $property->price == 0;

        /*
        |--------------------------------------------------------------------------
        | Step 1: Same town + narrow price range
        |--------------------------------------------------------------------------
        | - Primary similarity rule
        | - Rent: price <= current rent (ceiling applied)
        | - Sale: ±15% price range
        */
        if ($property->town) {

            $town_criteria = [
                'exclude_id' => json_encode($property->id),
                'in' => $property->town
            ];

            if (!$is_price_on_application) {
                $min_price = $property->price * 0.85;

                // IMPORTANT: Rent price ceiling enforced here
                $max_price = $is_rent_property
                    ? $property->price
                    : $property->price * 1.15;

                $town_criteria['price_range'] = "{$min_price}-{$max_price}";
            }

            $similar_properties = $propertyModel->searchWhere($town_criteria, FALSE, $limit);
        }

        /*
        |--------------------------------------------------------------------------
        | Step 2: Same town + wider price range
        |--------------------------------------------------------------------------
        | - Used when Step 1 does not return enough results
        | - Rent: still capped at current rent price
        | - Sale: wider ±30% range
        */
        if ($similar_properties->count() < $limit && !$is_price_on_application && $property->town) {

            $remaining = $limit - $similar_properties->count();

            $existing_ids = collect([$property->id])
                ->merge($similar_properties->pluck('id'))
                ->toArray();

            $max_price = $is_rent_property
                ? $property->price
                : $property->price * 1.3;

            $wider_price_criteria = [
                'exclude_id' => json_encode($existing_ids),
                'in' => $property->town,
                'price_range' => ($property->price * 0.7) . '-' . $max_price
            ];

            $wider_price_properties = $propertyModel->searchWhere($wider_price_criteria, FALSE, $remaining);
            $similar_properties = $similar_properties->merge($wider_price_properties);
        }

        /*
        |--------------------------------------------------------------------------
        | Step 3: Same town, no price restriction
        |--------------------------------------------------------------------------
        | - Last attempt within same town
        | - Price ignored, but RENT ceiling will be enforced later
        */
        if ($similar_properties->count() < $limit && $property->town) {

            $remaining = $limit - $similar_properties->count();

            $existing_ids = collect([$property->id])
                ->merge($similar_properties->pluck('id'))
                ->toArray();

            $location_only_criteria = [
                'exclude_id' => json_encode($existing_ids),
                'in' => $property->town
            ];

            $location_properties = $propertyModel->searchWhere($location_only_criteria, FALSE, $remaining);
            $similar_properties = $similar_properties->merge($location_properties);
        }

        /*
        |--------------------------------------------------------------------------
        | Step 4: Same country + price range
        |--------------------------------------------------------------------------
        | - Used when town-based results are insufficient
        | - Rent price ceiling still applied
        */
        if ($similar_properties->count() < $limit && $property->country) {

            $remaining = $limit - $similar_properties->count();

            $existing_ids = collect([$property->id])
                ->merge($similar_properties->pluck('id'))
                ->toArray();

            $country_criteria = [
                'exclude_id' => json_encode($existing_ids),
                'country' => $property->country
            ];

            if (!$is_price_on_application) {
                $max_price = $is_rent_property
                    ? $property->price
                    : $property->price * 1.3;

                $country_criteria['price_range'] = ($property->price * 0.7) . '-' . $max_price;
            }

            $country_properties = $propertyModel->searchWhere($country_criteria, FALSE, $remaining);
            $similar_properties = $similar_properties->merge($country_properties);
        }

        /*
        |--------------------------------------------------------------------------
        | Step 5: Final fallback (country only)
        |--------------------------------------------------------------------------
        | - Ensures we always try to return something
        | - Rent price ceiling will be enforced at the end
        */
        if ($similar_properties->count() < $limit) {

            $remaining = $limit - $similar_properties->count();

            $existing_ids = collect([$property->id])
                ->merge($similar_properties->pluck('id'))
                ->toArray();

            $fallback_criteria = [
                'exclude_id' => json_encode($existing_ids)
            ];

            if ($property->country) {
                $fallback_criteria['country'] = $property->country;
            }

            $fallback_properties = $propertyModel->searchWhere($fallback_criteria, FALSE, $remaining);
            $similar_properties = $similar_properties->merge($fallback_properties);
        }

        /*
        |--------------------------------------------------------------------------
        | Final Step: Enforce rent price ceiling (SAFETY GUARD)
        |--------------------------------------------------------------------------
        | - Ensures no rent property exceeds selected rent
        | - Protects against loose fallback matches
        */
        if ($is_rent_property) {
            $similar_properties = $similar_properties->filter(function ($item) use ($property) {
                return is_numeric($item->price) && $item->price <= $property->price;
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Final Fallback: Allow SALE properties if RENT is insufficient
        |--------------------------------------------------------------------------
        | - Only when current property is RENT
        | - Picks LOWEST priced sale properties first
        | - Maintains location relevance
        */
        if ($is_rent_property && $similar_properties->count() < $limit) {

            $remaining = $limit - $similar_properties->count();

            $existing_ids = collect([$property->id])
                ->merge($similar_properties->pluck('id'))
                ->toArray();

            $sale_fallback_criteria = [
                'exclude_id' => json_encode($existing_ids),
                'is_rental'  => 0 // SALE properties only
            ];

            // Prefer same town first
            if ($property->town) {
                $sale_fallback_criteria['in'] = $property->town;
            } elseif ($property->country) {
                $sale_fallback_criteria['country'] = $property->country;
            }

            // Fetch extra sale properties
            $sale_properties = $propertyModel
                ->searchWhere($sale_fallback_criteria, FALSE, $remaining)
                ->sortBy('price') // LOWEST price first
                ->take($remaining);

            $similar_properties = $similar_properties->merge($sale_properties);
        }

        /*
        |--------------------------------------------------------------------------
        | Ensure we return exactly $limit properties
        |--------------------------------------------------------------------------
        */
        return $similar_properties->take($limit);
    }

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $themeDirectory = themeOptions();
        $properties = collect([]);

        if ($property = $this->config['property']) {
            $properties = $this->findSimilarProperties($property, 3);
        }

        return view('frontend.' . $themeDirectory . '.widgets.similar_properties', [
            'config' => $this->config,
            'properties' => $properties,
            'is_similar' => true,
        ]);
    }
}