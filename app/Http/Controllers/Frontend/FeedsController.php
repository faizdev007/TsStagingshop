<?php

namespace App\Http\Controllers\Frontend;

class SimpleXMLExtended extends \SimpleXMLElement
{
    public function addCData($cdata_text)
    {
        $node = dom_import_simplexml($this);
        $no   = $node->ownerDocument;
        $node->appendChild($no->createCDATASection($cdata_text));
    }
}

use App\Property;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class FeedsController extends Controller
{
    public function kyero()
    {
        $is_set = settings('kyero_feed');

        if($is_set)
        {
            // Get properties
            $where = [
                ['status', '=', '0'], // Not inactive, not archived
                ['ref', '!=', ''], // Must have a ref
            ];

            $query = Property::where($where)->whereNull('archived_at');
            $properties = $query->get();

            // Open XML Tag....
            $xml = new SimpleXMLExtended('<root/>');
            $kyero = $xml->addChild('kyero');
            $kyero->addChild('feed_version', '3');

            // Listing...
            foreach($properties as $property)
            {
                $prop = $xml->addChild('property');
                $prop->addChild('id', $property->id);
                $prop->addChild('date', $property->created_at);
                $prop->addChild('ref', $property->ref);
                $prop->addChild('price', intval($property->price));
                $prop->addChild('currency', settings('currency'));

                // Is Sale Or Rent...
                if($property->is_rental == 1)
                {
                    $prop->addChild('price_freq', 'month');
                }
                else
                {
                    $prop->addChild('price_freq', 'sale');
                }

                $prop->addChild('part_ownership', '0');
                $prop->addChild('leasehold', '0');
                $prop->addChild('new_build', '0');
                $prop->addChild('type', strtolower($property->type->name));

                // Town / City...
                $town = $property->town;
                if(empty($town))
                {
                    $town = $property->city;
                }

                $prop->addChild('town', $town);

                // Province...
                $region = $property->region;

                if(empty($region))
                {
                    $region = $property->country;
                }

                $prop->addChild('province', $region);

                // Country (For Green-Acres)....
                $prop->addChild('country', $property->country);

                // Location Stuff...
                if($property->latitude && $property->longitude)
                {
                    if($property->latitude != 0 && $property->longitude != 0)
                    {
                        $location = $prop->addChild('location');
                        $location->addChild('latitude', $property->latitude);
                        $location->addChild('longitude', $property->longitude);
                    }
                }

                $prop->addChild('location_detail');
                $prop->addChild('beds', $property->beds);
                $prop->addChild('baths', $property->baths);

                // Surface Area...
                $surface_area = $prop->addChild('surface_area');
                $surface_area->addChild('built', (int)$property->internal_area);
                $surface_area->addChild('plot', (int)$property->land_area);

                // URL...
                $url = $prop->addChild('url');
                $url->addChild('en', $property->url);

                // Desc...
                $desc = $prop->addChild('desc');
                $en = $desc->addChild('en');

                $property_description = trim($property->description);
                $property_description = str_replace('&nbsp;', ' ', $property_description);
                $property_description = html_entity_decode($property_description);

                $en->addCData(strip_tags($property_description));

                // Images
                $images = $prop->addChild('images');

                $count = 1;

                if($property->propertyMedia)
                {
                    foreach($property->propertyMedia as $photo)
                    {
                        $image = $images->addChild('image');
                        $image->addAttribute('id', $count++);
                        $image->addChild('url', url($photo->path));
                    }
                }
            }

            header('Content-type: text/xml');

            $dom = dom_import_simplexml($xml)->ownerDocument;
            $dom->formatOutput = true;
            echo $dom->saveXML();
            exit;
        }
        else
        {
            // Not Found....
            App::abort(404);
        }

    }

    public function facebook()
    {
        // Get properties
        $where = [
            ['status', '=', '0'], // Not inactive, not archived
            ['ref', '!=', ''], // Must have a ref
        ];

        $query = Property::where($where)->whereNull('archived_at');
        $properties = $query->get();

        // Open XML Tag...
        $xml = new SimpleXMLExtended('<listings/>');
        $xml->addChild('title', settings('site_name') .' XML Feed');
        $link = $xml->addChild('link');
        $link->addAttribute('rel', 'self');
        $link->addAttribute('href', url(''));
        $xml->addChild('num_properties', count($properties));

        // Listing...
        $count = 0;

        foreach($properties as $property)
        {
            $listing = $xml->addChild('listing');
            $listing->addChild('home_listing_id', $property->id);
            $listing->addChild('home_listing_group_id', $property->ref);

            // Property Name...
            if($property->name)
            {
                $listing->addChild('name', $property->name);
            }
            else
            {
                $listing->addChild('name', strip_tags($property->search_headline_v2));
            }

            // Availability...
            $is_rental = $property->is_rental;

            if($is_rental == '0')
            {
                $availability = 'for_sale';
            }
            else
            {
                $availability = 'for_rent';
            }

            $listing->addChild('availability', $availability);

            // Desc (Strip Tags for XML Friendly)...
            $description = $property->description;
            $description = strip_tags($description);
            $description = str_replace('<','&lt;',$description);
            $description = str_replace('>','&gt;',$description);
            $description = str_replace('"','&quot;',$description);
            $description = str_replace("'",'&#39;',$description);
            $description = str_replace("&",'&amp;',$description);

            $listing->addChild('description', $description);

            // Address...
            $address = $listing->addChild('address');
            $address->addAttribute('format', 'simple');
            $address1 = $address->addChild('component', $property->street);
            $address1->addAttribute('name','addr1');
            $address1 = $address->addChild('component', $property->city);
            $address1->addAttribute('name','city');

            $region = $property->region;

            if(empty($region))
            {
                $region = $property->city;
            }

            $address1 = $address->addChild('component', $region);
            $address1->addAttribute('name','region');
            $address1 = $address->addChild('component', $property->country);
            $address1->addAttribute('name','country');
            $address1 = $address->addChild('component', $property->postcode);
            $address1->addAttribute('name','postal_code');

            $listing->addChild('latitude', $property->latitude);
            $listing->addChild('longitude', $property->longitude);

            $listing->addChild('neighbourhood', $property->region);

            // Image / Images..
            $image = $listing->addChild('image');

            $num_photos = 0;

            if(!$property->propertymedia->isEmpty())
            {
                foreach($property->propertymedia as $media)
                {
                    if($media->type == 'photo')
                    {
                        // Only Need 1 Pic....
                        $image->addChild('url', storage_url($media->path));
                        $num_photos ++;

                        // Max Of 20...
                        if($num_photos == 20)
                        {
                            break;
                        }
                    }
                }
            }

            // Listing Type...
            $listing_type = 'for_sale_by_agent';

            if($is_rental == '1')
            {
                $listing_type = 'for_rent_by_agent';
            }

            $listing->addChild('listing_type', $listing_type);

            $listing->addChild('num_baths', $property->baths);
            $listing->addChild('num_beds',$property->beds);

            // Optional Total Rooms (Beds + Baths?)
            //$num_rooms = $property->baths + $property->beds;
            // $listing->addChild('num_rooms', $num_rooms);

            // Price...
            $listing->addChild('price', round($property->price, 2) .' '. settings('currency'));

            // URL
            $listing->addChild('url', $property->url);

            // Property Type...
            $prop_type = $property->type->id;

            switch($prop_type)
            {
                case "1":
                    $property_type = "apartment";
                break;
                case "2":
                    $property_type = "house_in_villa";
                break;
                case "3":
                    $property_type = "other";
                break;
                case "6":
                    $property_type = "other";
                break;
                default:
                    $property_type = "house";
            }

            $listing->addChild('property_type', $property_type);

            $count ++;
        }

        header('Content-type: text/xml');

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        echo $dom->saveXML();
        exit;
    }

    public function brown_harris_feed()
    {
        // Get Properties...
        $properties = Property::where('export_bhs', '=', 1)
        ->where('status', '=', '0')
        ->get();

        $user = User::where('name', 'Nicole George')->first();

        // Open XML Tag...
        $xml = new SimpleXMLExtended('<Listings/>');

        foreach($properties as $property)
        {
            $listing = $xml->addChild('Listing');

            // Listing <Location>
            $location = $listing->addChild('Location');
            $location->addChild('StreetAddress', $property->street);
            //$location->addChild('UnitNumber', ''); // Not Set
            $location->addChild('City', isset($property->city) ? $property->city : $property->town);
            $location->addChild('StateProvince', $property->region);
            $location->addChild('Zip', $property->postcode); // May Need to come out...
            $location->addChild('Country', $property->country);
            $location->addChild('DisplayAddress', $property->name);

            // Listing <ListingDetails>
            $listing_details = $listing->addChild('ListingDetails');
            $listing_details->addChild('Status', $property->property_status);
            $listing_details->addChild('Price', number_format($property->price, 2, '.', ''));
            $listing_details->addChild('Currency', 'USD');
            $listing_details->addChild('ListingUrl', $property->url);
            $listing_details->addChild('MlsId', $property->ref);

            // Listing <BasicDetails>
            $basic_details = $listing->addChild('BasicDetails');
            $basic_details->addChild('Title', $property->name);
            $basic_details->addChild('PropertyType', $property->type->name);
            $description = $basic_details->addChild('Description');
            $description->addCData($property->description);
            $basic_details->addChild('Bedrooms', $property->beds);
            $basic_details->addChild('Bathrooms', $property->baths);
            $basic_details->addChild('FullBathrooms', number_format($property->beds, 1, '.', ''));
            $basic_details->addChild('HalfBathrooms', number_format($property->baths, 1, '.', ''));

            // Listing <Pictures>
            $images = $listing->addChild('Pictures');

            if($property->propertyMedia)
            {
                foreach($property->propertyMedia as $photo)
                {
                    $picture = $images->addChild('Picture');
                    $url = $picture->addChild('PictureUrl');
                    $url->addCData($photo->path);
                }
            }

            // Agent...
            $agent = $listing->addChild('Agent1');
            $agent->addChild('FirstName', $user->firstname);
            $agent->addChild('LastName', $user->lastname);
            $agent->addChild('EmailAddress', $user->email);
            $agent->addChild('CompanyName', 'Altman Barbados');
        }

        header('Content-type: text/xml');

        $dom = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;
        echo $dom->saveXML();
        exit;
    }
}
