<?php

namespace App\Traits;
use App\Enquiry;
use App\Mail\PortalAutoResponseMail;
use App\Models\ZooplaEtag;
use Carbon\Carbon;
use App\Property;
use App\PropertyType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Traits\CountryTrait;
use Illuminate\Support\Str;

trait ZooplaTrait
{
    use CountryTrait;

    private $branch_reference;
    private $current_time;
    private $environment;

    public function __construct()
    {
        $this->current_time = Carbon::now();
        $this->branch_reference = settings('zoopla_branch_reference');
        $this->environment = config('zoopla.ENVIRONMENT');
        $this->current_time = Carbon::now();
    }

    function update_feed($properties, $overseas = false)
    {
        // Log a number of properties being sent to Zoopla...
        Log::info('Adding or Updating '. count($properties). ' property to Zoopla');

        // Create a Zoopla Friendly Feed....
        foreach($properties as $property)
        {
            if($property->archived_at !== NULL)
            {
                // Delete Property...
                $result = $this->remove_property($property->ref);
            }
            else
            {
                $result = $this->send_property_details($property, $overseas);
            }
        }
    }

    private function send_property_details($property = false, $overseas = false)
    {
        $category = "residential"; // Residential by Default...

        // Break Postcode Up....
        $postcode = str_replace(' ', '', $property->postcode);
        $postcode_1 = substr($postcode, 0, -3);
        $postcode_2 = substr($postcode, -3);

        // Country....
        $country = $this->get_iso_name($property->country);

        // County
        if($property->region)
        {
            $county = $property->region;
        }
        else
        {
            $county = $property->city;
        }

        // Description, Limited to 320000 Chars...
        $description = $property->description;
        $description = substr($description, 0, 32000);

        // Status
        // Available; SSTC; Under Offer; Sold
        switch ($property->property_status)
        {
            case 'Sold':
                $status = 'under_offer';
            break;
            case 'SSTC':
                $status = 'sold_subject_to_contract';
                break;
            case 'Under Offer':
                $status = "under_offer";
                break;
            case 'Available':
                $status = 'available';
            default:
                $status = 'available';
            break;
        }

        // Property Name
        if($property->name !== NULL)
        {
            $property_name = $property->name;
        }
        else
        {
            $property_name = $property->display_address;
        }

        // If Rental Or Not....

        if($property->is_rental == 1)
        {
            if($property->rent_period == '3')
            {
                $frequency = 'per_month';
            }
            else
            {
                $frequency = 'per_week';
            }

            // Pricing For Sale Array...
            $pricing = array(
                'rent_frequency'    => $frequency,
                'currency_code'     => $this->get_currency_code($country),
                'price'             => $property->price,
                'transaction_type'  => "rent"
            );
        }
        else
        {
            // Pricing For Sale Array...
            $pricing = array(
                'currency_code'     => $this->get_currency_code($country),
                'price'             => $property->price,
                'transaction_type'  => "sale"
            );
        }

        $photos_array = array();
        foreach ($property->propertyMediaPhotos as $media)
        {
            $photos_array[] = array(
                'url'  => storage_url($media->path),
                'type' => 'image'
            );
        }

        $plans_array = array();
        foreach ($property->propertyMediaFloorplans as $media)
        {
            $plans_array[] = array(
                'url'       => storage_url($media->path),
                'type'      => 'floor_plan'
            );
        }

        $documents_array = array();
        foreach ($property->propertyMediaDocuments as $media)
        {
            $documents_array[] = array(
                'url'  => storage_url($media->path),
                'type'  => 'epc_report'
            );
        }

        $youtube_array = array();
        if($property->youtube_id)
        {
            $youtube_array[] = array(
                'url'  => 'https://youtube.com/watch?v='.$property->youtube_id,
                'type' => 'virtual_tour'
            );
        }

        $media_array = array_merge($photos_array, $plans_array, $documents_array, $youtube_array);

        // Create JSON Data Array...

        // Build Arrays....
        $address = array(
            'postal_code'               => $postcode_1 .' '. $postcode_2,
            'country_code'              => $country,
            'street_name'               => $property->street,
            'county'                    => $county,
            'property_number_or_name'   => $property_name,
            'town_or_city'              => $property->city,
            'coordinates' => array(
                'latitude'  => (float)$property->latitude,
                'longitude' => (float)$property->longitude,
            )
        );

        // Description...
        $prop_desc = array(
            array(
                'text' => $description
            )
        );

        $json_data = array(
            'branch_reference'      => $this->branch_reference, // Config Key Value
            'category'              => $category,
            'listing_reference'     => $property->ref,
            'location'              => $address,
            'pricing'               => $pricing,
            'property_type'         => $property->type->zoopla_type,
            'bathrooms'             => $property->baths,
            'content'               => $media_array,
            'detailed_description'  => $prop_desc,
            'display_address'       => $property->display_address,
            'life_cycle_status'     => $status,
            'total_bedrooms'        => $property->beds
        );

        // If Rental Append JSON....
        if($property->is_rental == 1)
        {
            $json_data['furnished_state'] = 'furnished_or_unfurnished';
        }

        if($property->type->id == '8')
        {
            $json_data['new_home'] = true;
        }

        //header("Content-type:application/json");
        $data = json_encode($json_data);

        if($this->environment == "test")
        {
            $endpoint = 'https://realtime-listings-api.webservices.zpg.co.uk/sandbox/v1/listing/update';
        }
        else
        {
            $endpoint = 'https://realtime-listings-api.webservices.zpg.co.uk/live/v1/listing/update';
        }

        // Json Schema....
        $profile = 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.1/schemas/listing/update.json';

        // Make an eTag....
        if($property->zooplatag)
        {
            // Has Tag, Compare New & Old...
            $curr_tag = $property->zooplatag->zoopla_etag;
            $new_tag = md5($data);

            if($curr_tag != $new_tag)
            {
                // New Tag...
                $response = $this->post_data($endpoint, $profile, $data);
            }
        }
        else
        {
            $response = $this->post_data($endpoint, $profile, $data);
        }

        if(isset($response))
        {
            $response = json_decode($response);

            if(isset($response->status))
            {
                if($response->status == "OK")
                {
                    if($response->new_listing == true)
                    {
                        // Save Log
                        Log::info('Property saved as new listing on Zoopla | Zoopla ID - '. $response->listing_reference. ' | Zoopla URL : '. $response->url );
                    }
                    else
                    {
                        // Save Log
                        Log::info('Property saved to Zoopla Successfully | Zoopla ID - '. $response->listing_reference. ' | Zoopla URL : '. $response->url );
                    }

                    // Create Zoopla eTag Record....
                    $etag = ZooplaEtag::firstOrNew(array('property_id' => $property->id));
                    $etag->property_id = $property->id;
                    $etag->zoopla_etag = $response->listing_etag;
                    $etag->zoopla_url = $response->url;
                    $etag->save();
                }
            }
            else
            {
                if(isset($response->error_name))
                {
                    // Errors - Log Them...
                    Log::error('Zoopla Error : '. $response->error_name . ' | Error Message : '. $response->error_advice);
                }
            }
        }
        else
        {
            Log::info('Did not send property to Zoopla - eTag is the same');
        }
    }

    private function list_properties()
    {
        $data = array(
            'branch_reference' => $this->branch_reference
        );

        $json_data = json_encode($data);

        if($this->environment == "test")
        {
            $endpoint = 'https://realtime-listings-api.webservices.zpg.co.uk/sandbox/v1/listing/list';
        }
        else
        {
            $endpoint = 'https://realtime-listings-api.webservices.zpg.co.uk/live/v1/listing/list';
        }

        // Json Schema....
        $profile = 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.1/schemas/listing/list.json';

        $response = $this->post_data($endpoint, $profile, $json_data);
    }

    private function remove_property($ref = false)
    {
        $data = array(
            'listing_reference' => $ref,
            'deletion_reason'   => 'withdrawn'
        );

        $json_data = json_encode($data);

        if($this->environment == "test")
        {
            $endpoint = 'https://realtime-listings-api.webservices.zpg.co.uk/sandbox/v1/listing/delete';
        }
        else
        {
            $endpoint = 'https://realtime-listings-api.webservices.zpg.co.uk/live/v1/listing/delete';
        }

        // Json Schema....
        $profile = 'http://realtime-listings.webservices.zpg.co.uk/docs/v1.1/schemas/listing/delete.json';

        $response = $this->post_data($endpoint, $profile, $json_data);

        if(isset($response))
        {
            Log::info('Removed Property - '. $ref .' from Zoopla');
        }
    }

    private function post_data($endpoint = false, $profile = false, $data = '')
    {
        $headers = array(
            'Content-Type: application/json; profile='. $profile,
            'Accept: application/json',
            'Content-Length: ' . strlen($data),
            'ZPG-Listing-ETag: ' . md5($data)
        );

        $error_msg = false;

        $curl_data = [
            CURLOPT_URL             => $endpoint,
            CURLOPT_PORT            => 443,
            CURLOPT_VERBOSE         => 0,
            CURLOPT_HEADER          => 0,
            CURLOPT_SSL_VERIFYPEER  => false,
            CURLOPT_SSLKEY          => config('zoopla.SSL_PEM'),
            CURLOPT_CAINFO          => '',
            CURLOPT_SSLCERTTYPE     => 'PEM',
            CURLOPT_SSLCERT         => config('zoopla.SSL_CERT'),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => "$data",
            CURLOPT_HTTPHEADER      => $headers
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $curl_data);
        $response = curl_exec($ch);

        //$errno = curl_errno($ch);
        //$error = curl_error($ch);
        //$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        usleep(500000);

        // Example Success...
//        $response = array(
//            'status'                => 'OK',
//              'listing_reference'   => 'PW-202',
//              'listing_etag'        => '6f3302f2430a17fde0f454502efb70b9',
//              'url'                 => 'http://realtime-listings.webservices.zpg.co.uk/preview/listing/1563187935910769/PW-202',
//              'new_listing'         => true
//        );

        return $response;

    }

    public function get_zoopla_leads()
    {
        // SFTP Connection
        $ftp = Storage::createFtpDriver(
            [
                'host'      => config('zoopla.FTP_HOST'),
                'username'  => config('zoopla.FTP_USER'),
                'password'  => config('zoopla.FTP_PASSWORD'),
                //'port'      => '21',
                //'passive'   =>  true,
                //'ssl'       => true,
                'timeout'   => '30',
                'root'      => '/public_html',
            ]
        );

        $files = $ftp->listContents('/mastersite/xml', true); // Change to FTP DIR given by Zoopla...

        // Create a file paths array...
        $file_paths = array();
        foreach($files as $file)
        {
            $file_paths[] = $file['path'];
        }

        foreach($file_paths as $file_path)
        {
            $file = $this->parse_zoopla_xml($ftp->read($file_path));

            // Finished with the file, Delete it from FTP....
            $delete_file = $ftp->delete($file_path);
        }
    }

    public function parse_zoopla_xml($file)
    {
        $xml = simplexml_load_string($file);

        $count = 0;
        foreach($xml->ZooplaLead as $zooplalead)
        {
            // Make Variables..
            // Category...
            $category = 'Zoopla - '. ucwords(str_replace('_', ' ', $zooplalead->TypeOfEnquiry));

            // Name...
            $name = $zooplalead->FirstName .' '. $zooplalead->LastName;

            // Email..
            $email = $zooplalead->FromEmail;

            // Telephone...
            $phone = $zooplalead->Phone;

            // Message...
            $message = 'Message : ' . $zooplalead->Message . ' '. 'Phone Number : '. $zooplalead->Phone . ' '. 'Best time to call : '. $zooplalead->BestTimeToCall;

            // Create Lead....
            $lead = new Enquiry;
            $lead->ref = $zooplalead->SourceListingId;
            $lead->category = $category;
            $lead->name = $name;
            $lead->email = $email;
            $lead->telephone = $phone;
            $lead->message = $message;
            $lead->url = '';
            $lead->created_at = \Illuminate\Support\Carbon::parse($zooplalead->LeadCreationDate);
            $lead->save();

            // Send Out Autoresponder to Customer....
            $property = Property::where('ref', $zooplalead->SourceListingId)->first();

            if($property)
            {
                Mail::to(trim($email))->send(new PortalAutoResponseMail($property, 'Zoopla', $zooplalead->FirstName));
            }

            $count ++;
        }

        Log::info('Added ' . $count .' leads from Zoopla');

        return true;
    }

}
