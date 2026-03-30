<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Property;

class DrawmapController extends Controller
{

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $current = url()->current();
        $request->session()->put('latest_search', $current);

        //metadata
        $meta_data = new \stdClass();
        $meta_data->title = 'Drawmaps';
        $meta = get_metadata($meta_data);

        return view('frontend.demo1.drawmap', [
            'meta' => $meta,
            'request' => $request
        ]);
    }


    public function get_properties(Request $request)
    {
        if($request->ajax()){
            $ne_lat = $request->input('ne_lat');
            $ne_lng = $request->input('ne_lng');
            $sw_lat = $request->input('sw_lat');
            $sw_lng = $request->input('sw_lng');
            $max_count = $request->input('max');

            $lat_min = (float) min ($ne_lat, $sw_lat);
            $lat_max = (float) max ($ne_lat, $sw_lat);
            $lng_min = (float) min ($ne_lng, $sw_lng);
            $lng_max = (float) max ($ne_lng, $sw_lng);

            $criteria = [];

            $criteria['for'] = ($request->input('mode') == 0) ? 'sale' : 'rent';
            $criteria['ne-lat'] = $ne_lat;
            $criteria['ne-lng'] = $ne_lng;
            $criteria['sw-lat'] = $sw_lat;
            $criteria['sw-lng'] = $sw_lng;

            $property = new Property();
            $properties = $property->searchWhere($criteria, FALSE, 200);
            $properties = $this->group_properties($properties);
            echo json_encode($properties);
        }
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

                if(count($p_same_latlng)){
                    foreach ($p_same_latlng as $same_p){
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
