<?php

namespace App\Traits;
use App\Enquiry;
use App\Mail\PortalAutoResponseMail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Property;
use App\PropertyType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Traits\CountryTrait;
use Illuminate\Support\Str;

trait RightMoveTrait
{
    private $network_id;
    private $branch_id;
    private $data_format;
    private $environment;
    private $allow_delete;
    private $current_time;

    public function __construct()
    {
        $this->network_id = config('rightmove.NETWORK_ID');

        $branch_id = config('rightmove.BRANCH_ID');

        if(empty($branch_id))
        {
            $this->branch_id = settings('rightmove_branch_id');
        }
        else
        {
            $this->branch_id = $branch_id;
        }

        $this->data_format = config('rightmove.FORMAT');
        $this->allow_delete = config('rightmove.ALLOW_DELETE');
        $this->environment = config('rightmove.ENVIRONMENT');

        $this->current_time = Carbon::now();

        $this->refs = array();
    }

    function update_feed($properties, $overseas = false)
    {
        // Process the Rightmove RTDF

        // Log number of properties being sent to RM...
        $message = sprintf('Adding or updating %d propert%s to Rightmove',
            count($properties),
            (count($properties) == 1 ? 'y' : 'ies')
        );
        Log::info($message);

        // 2. Send Property Details...
        foreach($properties as $property)
        {
            if($property->archived_at !== NULL)
            {
                if($this->allow_delete == true)
                {
                    // Log Delete Property...
                    Log::info('Attempt to Delete Property : '. $property->ref .' from Rightmove');

                    $result = $this->remove_property($property->ref);
                }
            }
            else
            {
                $result = $this->send_property_details($property, $overseas);
            }
        }

        echo "Finished!";

    }

    private function send_property_details($property = false, $overseas = false)
    {
        $agent_ref = $property->ref;

        // Set Variables to Match RightMove Fields & Such Like...
        $os_prefix = '';

        // Rental or Sale...
        $channel = 1; // Sales by Default...

        if($property->is_rental == 1)
        {
            $channel = 2; // Rental...
        }

        // Update Date....
        $update_date = ($property->updated_at ?: $property->created_at);

        // Published......
        // -1 = Inactive; 0 = Available; 1 = Archived
        switch ($property->status)
        {
            case '-1':
            case '1':
                $published = false;
                break;
            case '0':
            default:
                $published = true;
                break;
        }

        // Status
        // Available; SSTC; Under Offer; Sold
        switch ($property->property_status)
        {
            case 'Sold':
            case 'SSTC':
                $status = 2;
                break;
            case 'Under Offer':
                $status = 4;
                break;
            case 'Available':
            default:
                $status = 1;
                break;
        }

        // Qualifer...
        switch ($property->price_qualifier)
        {
            case 'POA':
                $price_qualifier = '1';
                break;
            case 'Guide Price':
                $price_qualifier = '2';
                break;
            case 'Fixed Price':
                $price_qualifier = '3';
                break;
            case 'Offers in excess of':
                $price_qualifier = '4';
                break;
            case 'OIRO':
                $price_qualifier = '5';
                break;
            case 'Sale by Tender':
                $price_qualifier = '6';
                break;
            case 'From':
                $price_qualifier = '7';
                break;
            case 'Shared Ownership':
                $price_qualifier = '9';
                break;
            case 'Offers over':
                $price_qualifier = '10';
                break;
            case 'Part Buy':
                // case 'Part Rent':
                $price_qualifier = '11';
                break;
            case 'Shared Equity':
                $price_qualifier = '12';
                break;
            case 'Coming Soon':
                $price_qualifier = '16';
                break;
            case 'Default':
            default:
                $price_qualifier = '0';
                break;
        }

        $postcode = str_replace(' ', '', $property->postcode);
        $postcode_1 = substr($postcode, 0, -3);
        $postcode_2 = substr($postcode, -3);

        // Property Summary (Strip Tags)....
        $summary = $property->description;
        $summary = strip_tags($summary);
        $summary = html_entity_decode($summary);
        $summary = substr($summary, 0, 997).'...';

        // Description, Limited to 320000 Chars...
        $description = $property->description;
        $description = substr($description, 0, 32000);

        if($this->data_format == 'xml')
        {
            $w = new \XMLWriter;
            $w->openMemory();
            $w->startDocument('1.0', 'UTF-8');
            $w->startElement('addUpdatePropertyForm');
            $w->startElement('network');
            $w->writeElement('network_id', NETWORK_ID);
            $w->endElement(); // network

            $w->startElement('branch');
            $w->writeElement('branch_id', BRANCH_ID);
            $w->writeElement('channel', CHANNEL);
            $w->endElement(); // branch

            $w->startElement('property');
            $agent_ref = $property->ref;
            $w->writeElement('agent_ref', $agent_ref);
            $w->writeElement('published', $published);
            $w->writeElement('property_type', $property->type->rightmove_number);
            $w->writeElement('status', $status);

            $create_date = $this->current_time;
            $update_date = ($property->updated_at ?: $property->created_at);
            $w->writeElement('create_date', $create_date);
            $w->writeElement('update_date', $update_date);

            $w->startElement('address');
            $postcode_1 = substr($postcode, 0, -3);
            $postcode_2 = substr($postcode, -3);
            $w->writeElement('house_name_number', $property->house_name_number);
            $w->writeElement('address_2', $property->street);
            $w->writeElement('address_3', $property->town);
            $w->writeElement('address_4', $property->region);
            $w->writeElement('town', $property->city); // As agreed with James
            $w->writeElement('postcode_1', $postcode_1);
            $w->writeElement('postcode_2', $postcode_2);
            $w->writeElement('display_address', $property->display_address);
            $w->writeElement('latitude', $property->latitude);
            $w->writeElement('longitude', $property->longitude);
            $w->endElement(); // address

            $w->startElement('price_information');
            $w->writeElement('price', $property->price);

            $w->writeElement('price_qualifier', $price_qualifier);
            $w->endElement(); // price_information

            $w->startElement('details');
            $w->startElement('summary');
            $w->writeCData($summary);
            $w->endElement(); // summary
            $w->startElement('description');
            $w->writeCData($description);
            $w->endElement(); // description
            $w->writeElement('bedrooms', (int) $property->beds);
            $w->writeElement('bathrooms', (int) $property->baths);
            $w->writeElement('internal_area', (float) $property->internal_area);
            $w->writeElement('internal_area_unit', '2');
            $w->writeElement('land_area', (float) $property->land_area);
            $w->writeElement('land_area_unit', '2');
            $w->endElement(); // details

            $media_counter = 0;
            foreach ($property->propertyMediaPhotos as $media)
            {
                $media_update_date = $this->format_rightmove_datetime($media->updated_at);

                $w->startElement('media');
                $w->writeElement('media_type', '1');
                $w->writeElement('media_url', storage_url($media->path));
                $w->writeElement('caption', $media->title);
                $w->writeElement('sort_order', ++$media_counter);
                $w->writeElement('media_update_date', $media_update_date);
                $w->endElement(); // media
            }

            $media_counter = 0;
            foreach ($property->propertyMediaFloorplans as $media)
            {
                $media_update_date = $this->format_rightmove_datetime($media->updated_at);

                $w->startElement('media');
                $w->writeElement('media_type', '2');
                $w->writeElement('media_url', storage_url($media->path));
                $w->writeElement('caption', $media->title);
                $w->writeElement('sort_order', ++$media_counter);
                $w->writeElement('media_update_date', $media_update_date);
                $w->endElement(); // media
            }

            $media_counter = 0;
            foreach ($property->propertyMediaDocuments as $media)
            {
                $media_update_date = $this->format_rightmove_datetime($media->updated_at);

                $w->startElement('media');
                $w->writeElement('media_type', '3');
                $w->writeElement('media_url', storage_url($media->path));
                $w->writeElement('caption', $media->title);
                $w->writeElement('sort_order', ++$media_counter);
                $w->writeElement('media_update_date', $media_update_date);
                $w->endElement(); // media
            }

            if ($property->youtube_id)
            {
                $w->startElement('media');
                $w->writeElement('media_type', '4');
                $w->writeElement('media_url', 'https://youtube.com/watch?v='.$property->youtube_id);
                $w->writeElement('caption', 'Visual Tour');
                $w->writeElement('sort_order', 1);
                $w->endElement(); // media
            }

            $w->endElement(); // property
            $w->endElement(); // addUpdatePropertyForm
            $w->endDocument();
            $data = $w->outputMemory(TRUE);
            // header('Content-type: application/xml; charset=UTF-8');
            // exit($xmlout);

            $xml = simplexml_load_string($response);
            if ($xml)
            {
                printf('- %s<br>', (string)$xml->property->rightmove_url);
                $result = $xml->xpath('/warnings');
            }

            // echo '<pre style="margin:10px 0;padding:10px;background:#eee">';
            // echo htmlspecialchars($response);
            // echo '</pre>';
        }

        if($this->data_format == 'json')
        {
            $json_data = array();

            if($overseas == true)
            {
                // Different Settings for Overseas Properties...
                $os_prefix = 'os_';

                $branch = array
                (
                    'branch_id' => $this->branch_id,
                );

                // Base Detail for Properties on Overseas...
                $address = array(
                    'country_code'      => $this->getget_iso_name(settings('overseas_country')), // Get from Global Settings....
                    'region'            => $property->region,
                    'sub_region'        => $property->region, // May Change?
                    'town_city'         => $property->town,
                    'latitude'          => $property->latitude,
                    'longitude'         => $property->longitude
                );
            }
            else
            {
                // UK Based Property....
                $branch = array
                (
                    'branch_id' => $this->branch_id,
                    'channel'   => $channel,
                    'overseas'  => $overseas
                );

                $address = array(
                    'house_name_number' => ($property->name) ? $property->search_headline : $property->search_headline,
                    'address_2'         => $property->street,
                    'address_3'         => $property->town,
                    'address_4'         => $property->region,
                    'town'              => $property->city,
                    'postcode_1'        => $postcode_1,
                    'postcode_2'        => $postcode_2,
                    'display_address'   => $property->display_address,
                    'latitude'          => $property->latitude,
                    'longitude'         => $property->longitude
                );
            }

            $media_counter = 0;
            $photos_array = array();
            foreach ($property->propertyMediaPhotos as $media)
            {
                $photos_array[] = array(
                    'media_type' => '1',
                    'media_url'  => storage_url($media->path),
                    'caption'   => $media->title,
                    'sort_order' => ++$media_counter,
                    'media_update_date' => $this->format_rightmove_datetime($media->updated_at)
                );
            }

            $media_counter = 0;
            $plans_array = array();
            foreach ($property->propertyMediaFloorplans as $media)
            {
                $plans_array[] = array(
                    'media_type' => '2',
                    'media_url'  => storage_url($media->path),
                    'caption'   => $media->title,
                    'sort_order' => ++$media_counter,
                    'media_update_date' => $this->format_rightmove_datetime($media->updated_at)
                );
            }

            $media_counter = 0;
            $documents_array = array();
            foreach ($property->propertyMediaDocuments as $media)
            {
                $documents_array[] = array(
                    'media_type' => '2',
                    'media_url'  => storage_url($media->path),
                    'caption'   => $media->title,
                    'sort_order' => ++$media_counter,
                    'media_update_date' => $this->format_rightmove_datetime($media->updated_at)
                );
            }

            $youtube_array = array();
            if($property->youtube_id)
            {
                $youtube_array[] = array(
                    'media_type' => '4',
                    'media_url'  => 'https://youtube.com/watch?v='.$property->youtube_id,
                    'caption'   => 'Visual Tour'
                );
            }

            $media_array = array_merge($photos_array, $plans_array, $documents_array, $youtube_array);

            if($this->data_format == 'json')
            {
                $json_data = array
                (
                    'network' => array
                    (
                        'network_id' => config('rightmove.NETWORK_ID')
                    ),
                    'branch' => $branch,
                    'property' => array
                    (
                        'agent_ref' => $agent_ref,
                        'published' => $published,
                        'property_type' => $property->type->rightmove_number,
                        $os_prefix.'status'   => $status,
                        'create_date' => $this->format_rightmove_datetime($this->current_time),
                        'update_date' => $this->format_rightmove_datetime($update_date),
                        'address' => $address,
                        'price_information' => array
                        (
                            'price' => $property->price,
                            $os_prefix.'price_qualifier' => $price_qualifier
                        ),
                        'details' => array(
                            'summary'               => $summary,
                            'description'           => $description,
                            'bedrooms'              => (int)$property->beds,
                            'bathrooms'             => (int)$property->baths,
                            'internal_area'         => (float)$property->internal_area,
                            'internal_area_unit'    => '2',
                            'land_area'             => (float)$property->land_area,
                            'land_area_unit'        => '2',
                        ),
                        'media' => $media_array
                    )
                );

                //header("Content-type:application/json");
                $data = json_encode($json_data);
                //echo $data;
            }
        }

        if($this->environment == "test")
        {
            $endpoint = 'https://adfapi.adftest.rightmove.com/v1/property/sendpropertydetails';

            if($overseas == true)
            {
                $endpoint = 'https://adfapi.adftest.rightmove.com/v1/property/overseassendpropertydetails';
            }
        }
        else
        {
            $endpoint = 'https://adfapi.rightmove.co.uk/v1/property/sendpropertydetails';

            if($overseas == true)
            {
                $endpoint = 'https://adfapi.rightmove.co.uk/v1/property/overseassendpropertydetails';
            }

        }

        $response = $this->post_rightmove($endpoint, $data, $this->data_format);

        if($this->data_format == 'json')
        {
            // Log Response Info from Rightmove (Trap Errors / Warnings)...
            if($response)
            {
                $response = json_decode($response);

                if($response->warnings !== NULL)
                {
                    foreach($response->warnings as $warning)
                    {
                        Log::warning('Rightmove Warning Code : '. $warning->warning_code . ' | Warning Val : '. $warning->warning_value. ' | Warning Message : '. $warning->warning_description);
                    }
                }

                if($response->errors !== NULL)
                {
                    foreach($response->errors as $error)
                    {
                        Log::error('Rightmove Error Code : '. $error->error_code . ' | Error Val : '. $error->error_value. ' | Error Message : '. $error->error_description);
                    }
                }

                if($response->success == true)
                {
                    $change_type = strtolower($response->property->change_type);

                    Log::info('Property '.$change_type.' to Rightmove Successfully | Rightmove ID - '. $response->property->rightmove_id. ' | Rightmove URL : '. $response->property->rightmove_url );
                }
            }
        }

        return $agent_ref;

    }

    private function get_leads($lead_type)
    {
        // Make Some Dates...
        $start = Carbon::now()->subHours(1); // Debug ->subDays(2);
        $start = $start->format('d-m-Y H:i:s');

        $end = Carbon::now();
        $end = $end->format('d-m-Y H:i:s');

        if($lead_type == 'emails')
        {
            $json_data = array(
                'network' => array(
                    'network_id' => config('rightmove.NETWORK_ID')
                ),
                'branch' => array(
                    'branch_id' => settings('rightmove_branch_id'),
                ),
                'export_period' => array(
                    'start_date_time' => $start,
                    'end_date_time'   => $end
                )
            );

            //header("Content-type:application/json");
            $data = json_encode($json_data);
            //echo $data;

            if($this->environment == "test")
            {
                $endpoint = 'https://adfapi.adftest.rightmove.com/v1/property/getbranchemails';
            }
            else
            {
                $endpoint = 'https://adfapi.rightmove.co.uk/v1/property/getbranchemails';
            }

            $response = $this->post_rightmove($endpoint, $data, 'json');

            if($response)
            {
                $response = json_decode($response);

                if($response->success == true)
                {
                    // See If we have any Emails...
                    $emails = $response->emails;

                    if(is_array($emails))
                    {
                        $count = 0;

                        // Loop Through The Emails...
                        foreach($emails as $email)
                        {
                            // Create New Lead...
                            $lead = Enquiry::firstOrNew(array('ref' => $email->email_id));
                            $lead->ref = $email->property->agent_ref;
                            $lead->category = 'Rightmove Property Enquiry';
                            $lead->name = $email->user->user_contact_details->first_name . ' '. $email->user->user_contact_details->last_name;
                            $lead->email = $email->from_address;
                            $lead->telephone = ($email->user->user_contact_details->phone_day ?? $email->user->user_contact_details->phone_evening);
                            $lead->message = trim(preg_replace('/\s\s+/', ' ', $email->user->user_information->comments));
                            $lead->url = $email->property->rightmove_url;
                            $lead->created_at = Carbon::parse($email->email_date);
                            $lead->save();

                            if($lead->wasRecentlyCreated)
                            {
                                // Send Out Autoresponder to Customer....
                                $property = Property::where('ref', $email->property->agent_ref)->first();

                                if($property)
                                {
                                    Mail::to(trim($email))->send(new PortalAutoResponseMail($property, 'Rightmove', $email->user->user_contact_details->first_name));
                                }

                                $count ++;
                            }
                        }

                        if($count > 0)
                        {
                            Log::info("Added - $count Lead Emails from Rightmove");
                        }
                    }

                }
            }
        }
        else
        {
            // Phone Leads....
        }
    }

    private function get_branch_property_list()
    {
        if($this->data_format == 'xml')
        {
            $w = new \XMLWriter;
            $w->openMemory();
            $w->startDocument('1.0', 'UTF-8');
            $w->startElement('getBranchPropertyListForm');
            $w->startElement('network');
            $w->writeElement('network_id', config('rightmove.NETWORK_ID'));
            $w->endElement(); // network
            $w->startElement('branch');
            $w->writeElement('branch_id', config('rightmove.BRANCH_ID'));
            $w->writeElement('channel', 1);
            $w->endElement(); // branch
            $w->endElement(); // getBranchPropertyListForm
            $w->endDocument();
            $data = $w->outputMemory(TRUE);
        }

        if($this->data_format == 'json')
        {
            $json_data = array(
                'network' => array(
                    'network_id' => config('rightmove.NETWORK_ID')
                ),
                'branch' => array(
                    'branch_id' => $this->branch_id,
                    'channel'   => 1
                )
            );

            $data = json_encode($json_data);
        }

        if($this->environment == "test")
        {
            $endpoint = 'https://adfapi.adftest.rightmove.com/v1/property/getbranchpropertylist';
        }
        else
        {
            $endpoint = 'https://adfapi.rightmove.co.uk/v1/property/getbranchpropertylist';
        }

        return $response = $this->post_rightmove($endpoint, $data);
    }

    private function remove_property($ref = false)
    {
        if($this->data_format == 'xml')
        {
            $w = new \XMLWriter;
            $w->openMemory();
            $w->startDocument('1.0', 'UTF-8');
            $w->startElement('removePropertyForm');
            $w->startElement('network');
            $w->writeElement('network_id', NETWORK_ID);
            $w->endElement(); // network
            $w->startElement('branch');
            $w->writeElement('branch_id', BRANCH_ID);
            $w->writeElement('channel', CHANNEL);
            $w->endElement(); // branch
            $w->startElement('property');
            $w->writeElement('agent_ref', $ref);
            $w->writeElement('removal_reason', 11);
            $w->writeElement('transaction_date', '');
            $w->endElement(); // property
            $w->endElement(); // removePropertyForm
            $w->endDocument();
            $data = $w->outputMemory(TRUE);
            // header('Content-type: application/xml; charset=UTF-8');
            // exit($xmlout);
        }

        if($this->data_format == 'json')
        {
            $json_data = array(
                'network' => array(
                    'network_id' => config('rightmove.NETWORK_ID')
                ),
                'branch' => array(
                    'branch_id' => $this->branch_id,
                    'channel'   => 1
                ),
                'property' => array(
                    'agent_ref' => $ref,
                    'removal_reason' => 11,
                    'transaction_date' => $this->format_rightmove_datetime($this->current_time)
                )
            );

            $data = json_encode($json_data);
        }


        if($this->environment == "test")
        {
            $endpoint = 'https://adfapi.adftest.rightmove.com/v1/property/removeproperty';
        }
        else
        {
            $endpoint = 'https://adfapi.rightmove.co.uk/v1/property/removeproperty';
        }

        $response = $this->post_rightmove($endpoint, $data);

        if($this->data_format == 'json')
        {
            // Log Response Info from Rightmove (Trap Errors / Warnings)...
            if($response)
            {
                $response = json_decode($response);

                if($response->warnings !== NULL)
                {
                    foreach($response->warnings as $warning)
                    {
                        Log::warning('Rightmove Warning Code : '. $warning->warning_code . ' | Warning Val : '. $warning->warning_value. ' | Warning Message : '. $warning->warning_description);
                    }
                }

                if($response->errors !== NULL)
                {
                    foreach($response->errors as $error)
                    {
                        Log::error('Rightmove Error Code : '. $error->error_code . ' | Error Val : '. $error->error_value. ' | Error Message : '. $error->error_description);
                    }
                }

                if($response->success == true)
                {
                    Log::info('Property Delete from Rightmove Successfully | Rightmove ID - '. $response->property->rightmove_id. '');
                }
            }
        }

        return $response;
    }

    private function post_rightmove($endpoint = false, $data = '', $format)
    {

        if($format == 'json')
        {
            $headers = array(
                'Content-Type: application/json',
                'Accept: application/json'
            );
        }
        else
        {
            // XML....
            $headers = array(
                'Content-Type: application/xml',
                'Accept: application/xml'
            );
        }

        $error_msg = false;
        $curl_data = [
            CURLOPT_URL             => $endpoint,
            CURLOPT_HEADER          => 0,
            CURLOPT_POST            => 1,
            CURLOPT_POSTFIELDS      => "$data",
            CURLOPT_VERBOSE         => true,
            CURLOPT_RETURNTRANSFER  => 1,
            //CURLOPT_USERPWD         => 'rightmove:rightpass', // Only Needed on TEST Environment
            CURLOPT_HTTPAUTH        => CURLAUTH_BASIC,
            CURLOPT_SSLCERT         => config('rightmove.CERT_FILE'),
            CURLOPT_SSLCERTTYPE     => 'PEM',
            CURLOPT_SSLKEYPASSWD    => config('rightmove.CERT_PASS'),
            CURLOPT_SSL_VERIFYHOST  => 0,
            CURLOPT_SSL_VERIFYPEER  => 0,
            CURLOPT_HTTPHEADER      => $headers
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $curl_data);
        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // dd(curl_getinfo($ch));
        curl_close($ch);
        // header('Content-type: application/xml; charset=UTF-8');
        // exit($response);

        usleep(500000);

        return $response;
    }

    private function format_rightmove_datetime($date_object)
    {
        return $date_object->format('d-m-Y H:i:s');
    }

}
