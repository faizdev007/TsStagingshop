<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class FacebookCatalogExportController extends Controller
{
    public function showForm()
    {
        // Get all active properties, ordered by ref descending
        $properties = Property::where('status', '>=', 0)
                            ->where('status', '!=', 1)
                            ->whereNull('archived_at')
                            ->orderBy('ref', 'desc')
                            ->select(['ref', 'name', 'price', 'town', 'city', 'is_rental', 'beds', 'baths', 'id', 'complex_name', 'country'])
                            ->get();

        // Format the data for display
        $properties->transform(function ($property) {
            $property->location = $property->town && $property->city 
                ? $property->town . ', ' . $property->city 
                : ($property->town ?: $property->city);
            return $property;
        });

        return view('frontend.demo1.page-templates.facebook-catalog-form', compact('properties'));
    }

    public function exportToXML(Request $request)
    {
        // Validate request
        $request->validate([
            'properties' => 'required|array',
            'properties.*' => 'required|string',
            'image_urls' => 'array',
            'image_urls.*' => 'nullable|url'
        ]);

        // Get selected properties
        $properties = Property::whereIn('ref', $request->properties)
                            ->where('status', '>=', 0)
                            ->where('status', '!=', 1)
                            ->whereNull('archived_at')
                            ->get();

        // Create XML
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><listings></listings>');

        foreach ($properties as $property) {
            $listing = $xml->addChild('listing');
            
            // Required fields
            $listing->addChild('home_listing_id', htmlspecialchars('S-' . ltrim($property->ref, 'S-')));
            
            // Name - use the actual name or generate from features
            $name = $property->name;
            if (empty($name)) {
                $features = [];
                if ($property->beds) $features[] = $property->beds . ' Bedroom';
                if ($property->town) $features[] = $property->town;
                $features[] = $property->is_rental ? 'Rental Property' : 'Property for Sale';
                $name = implode(' ', $features);
            }
            $listing->addChild('name', htmlspecialchars($name));
            
            // Availability
            $listing->addChild('availability', $property->is_rental ? 'for_rent' : 'for_sale');


            
            // Image URL from request - Must come before price for Facebook's ordering
            if (!empty($request->image_urls[$property->ref])) {
                $image = $listing->addChild('image');
                $image->addChild('url', htmlspecialchars($request->image_urls[$property->ref]));
            }
            
            // Price
            if ($property->price) {
                $listing->addChild('price', number_format($property->price, 2) . ' AED');
            }
            
            // Property URL - using proper slug format
            $slug = Str::slug($property->name ?: "property-{$property->ref}");
            $listing->addChild('url', url("property/{$slug}/{$property->id}"));
            
            // Address with proper format
            $address = $listing->addChild('address');
            $address->addAttribute('format', 'simple');

            // Use town as Complex
            if ($property->town) {
            $address->addChild('component', htmlspecialchars($property->complex_name))->addAttribute('name', 'addr1');
            }

                        // Use town as Complex
                        if ($property->town) {
                            $address->addChild('component', htmlspecialchars($property->town))->addAttribute('name', 'addr2');
                            }
            
            // Always add Dubai as city
            $address->addChild('component', 'Dubai')->addAttribute('name', 'city');
            
            // Use town as region
            if ($property->town) {
                $address->addChild('component', htmlspecialchars($property->country))->addAttribute('name', 'region');
                }
            
            // Add country
            if ($property->town) {
                $address->addChild('component', htmlspecialchars($property->country))->addAttribute('name', 'country');
                }

            // Optional fields
            if ($property->beds) {
                $listing->addChild('num_beds', $property->beds);
            }
            if ($property->baths) {
                $listing->addChild('num_baths', $property->baths);
            }
        }

        // Generate response
        $response = Response::make($xml->asXML(), 200);
        $response->header('Content-Type', 'text/xml');
        $response->header('Content-Disposition', 'attachment; filename="facebook_catalog.xml"');

        return $response;
    }
}
