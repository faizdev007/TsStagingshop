<?php

namespace App\Http\Controllers\Frontend;

use App\Property;
use App\PropertyType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MapSearchController extends Controller
{
    public function search(Request $request)
    {
        $data = $request->except('_token', '_method');

        $find_array = array('LatLng', '(', ')', ' ');

        $ne_coords = str_replace($find_array, '', $data['ne_lat']);
        $ne_coords = explode(',', $ne_coords);
        $sw_coords = str_replace($find_array, '', $data['sw_lat']);
        $sw_coords = explode(',', $sw_coords);

        $ne_lat = $ne_coords[0];
        $ne_lng = $ne_coords[1];

        $sw_lat = $sw_coords[0];
        $sw_lng = $sw_coords[1];

        $map_lat = '';
        $map_lng = '';

        $criteria = [];

        $criteria['for'] = ($request->input('for') == 'sale') ? 'sale' : 'rent';

        if($request->input('property_type'))
        {
            $property_type = PropertyType::where('slug', $request->input('property_type'))->select('id')->first();
            $criteria['property_type_id'] = $property_type->id;
        }

        if($request->has('in'))
        {
            $params = array(
                'address' => urlencode($request->input('in')),
                'key'     => settings('google_map_api')
            );

            // Use Google GeoCoding to Get Location Data....
            $geocode_url = 'https://maps.googleapis.com/maps/api/geocode/json';
            $query_str = http_build_query($params);
            $geocode_url .= '?' . $query_str;

            $geo_data = @file_get_contents($geocode_url);

            // TESTING....
            //$geo_data = @file_get_contents(public_path('location-data.json'));

            if($geo_data === false)
            {
                Log::error('Geocode Issue');

                return false;
            }
            else
            {
                $json_data = json_decode($geo_data);
                if($json_data)
                {
                    foreach($json_data->results as $result)
                    {
                        // Set Lat / Longs...
                        $ne_lat = $result->geometry->bounds->northeast->lat;
                        $ne_lng = $result->geometry->bounds->northeast->lng;

                        $sw_lat = $result->geometry->bounds->southwest->lat;
                        $sw_lng = $result->geometry->bounds->southwest->lng;

                        $map_lat = $result->geometry->location->lat;
                        $map_lng = $result->geometry->location->lng;
                    }

                    $request->request->remove('in');
                }
            }
        }

        $lat_min = (float) min ($ne_lat, $sw_lat);
        $lat_max = (float) max ($ne_lat, $sw_lat);
        $lng_min = (float) min ($ne_lng, $sw_lng);
        $lng_max = (float) max ($ne_lng, $sw_lng);

        //$criteria['in'] = $request->input('in');
        $criteria['price_range'] = $request->input('price_range');
        $criteria['beds'] = $request->input('beds');
        $criteria['ne-lat'] = $ne_lat;
        $criteria['ne-lng'] = $ne_lng;
        $criteria['sw-lat'] = $sw_lat;
        $criteria['sw-lng'] = $sw_lng;
        $criteria['max'] = '200'; // Change (IF Needed)...

        $criteria = array_filter($criteria);
        //dump($criteria);

        $property = new Property();
        $properties = $property->searchWhere($criteria, FALSE);
        $properties = $this->group_properties($properties);

        return response()->json(['properties' => $properties, 'map_lat' => $map_lat, 'map_lng' => $map_lng]);
    }

    //Group properties
    private function group_properties($properties)
    {
        $array = [];
        $map_query = [];

        foreach ($properties as $property):

            if( !empty($property->latitude) && !empty($property->longitude) ){

                $p_same_latlng = $this->similar_latlng($properties, array('lat'=>$property->latitude,'lng'=>$property->longitude, 'id'=>$property->id));

                $html = "<div class='info-con'>";
                $property_item = $this->format_infowindow($property);
                $html .= $property_item;

                if(count($p_same_latlng))
                {
                    foreach ($p_same_latlng as $same_p)
                    {
                        $property_item = $this->format_infowindow($same_p, 'add');
                        $html .= $property_item;
                    }
                }
                $html .= '</div>';

                $map_query = [
                    'id' => $property->id,
                    'latitude' => $property->latitude,
                    'longitude' => $property->longitude,
                    'html' => $html,
                ];
                $array[] = $map_query;
            }

        endforeach;


        return $array;
    }

    //Format HTML
    private function format_infowindow($property, $add='')
    {
        $html = '<div class="info-dm '.$add.'">';
        $html .= '<div class="image"><a href="'.$property->url.'"><img src='.($property->primary_photo).' alt='.$property->search_headline.'></a></div>';
        $html .= '<div class="title"><a href="'.$property->url.'">'.$property->search_headline.'</a></div>';
        $html .= '<div class="price">'.$property->display_price.'</div>';
        $bedBath_array = [];
        if($property->baths != 0){
            $bedBath_array[] = 'Baths '.$property->baths;
        }
        if($property->beds != 0){
            $bedBath_array[] = 'Beds '.$property->beds;
        }
        $bedBath = (count($bedBath_array)) ? implode(' | ',$bedBath_array) : '';
        $html .= '<div class="attribute">';
        $html .= $bedBath;
        $html .= '</div>';
        $html .= '<div class="cta-d">';
        $html .= '<a href="'.$property->url.'">View Property</a>';
        $html .= '</div>';
        $html .= '</div>';
        return $html;
    }

    //Get similar lat lng properties
    private function similar_latlng($properties, $latlng)
    {
        $properties_return = array();
        foreach ($properties as $property):
            if($property->latitude==$latlng['lat'] && $property->longitude==$latlng['lng'] && $latlng['id'] != $property->id){
                $properties_return[] = $property;
            }
        endforeach;
        return $properties_return;
    }
}
