<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UKPostcodeTrait
{
    /**
     * This Function gets the Latitude Of a place from the postcode
     * This function uses Mapit API
     */

    function get_lat_long($postcode)
    {
        // Prepare the postcode to send to MapIt
        $postcode = urlencode($postcode);

        // Remove any Spaces from the Postcode String
        $postcode = str_replace(' ', '', $postcode);

        // Set the URI for the API Call
        $uri = 'http://mapit.mysociety.org/postcode/'.$postcode;

        // Get the Results and return as a JSON Object
        $json_data = @file_get_contents($uri);

        // Decode The JSON Object
        $json_output = json_decode($json_data);

        // If the postcode is invalid
        if(!empty($json_output->code))
        {
            // If the API returns 404 (Not Found), 400 (Bad Request) or 403 (Forbidden) we return false to prevent
            // further errors occurring in the core
            if( ($json_output->code == '404') OR ($json_output->code == '400') OR ($json_output->code == '403') )
            {
                return 'Unavailable';
            }
        }

        if($json_output)
        {
            $latitude = $json_output->wgs84_lat;
            $longitude = $json_output->wgs84_lon;

            $return_data = array(
                'latitude'	=> $latitude,
                'longitude'	=> $longitude
            );

            return $return_data;
        }
    }
}