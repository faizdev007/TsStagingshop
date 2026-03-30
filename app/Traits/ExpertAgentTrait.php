<?php

namespace App\Traits;

use App\Traits\FeedTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Branch;
use App\Property;
use App\PropertyType;
use Illuminate\Support\Str;

trait ExpertAgentTrait
{
    use FeedTrait;

    public function import()
    {
        // possibly put this one in config file:
        $disallowed_branches = [];

        $refs = [];
        $user_id = 0; // or 102

        // Get property types from database
        $_property_types = PropertyType::all()->pluck('name', 'id')->toArray();
        asort($_property_types);

        // Feed file(s) to get from the FTP account
        $feed_files = config('expertagent.feed_files');
        foreach ($feed_files as $feed_id => $feed_file)
        {
            $this->info(sprintf('Feed ID %d "%s"', $feed_id, $feed_file));

            // get the contents of this feed
            try
            {
                $xml = $this->fetch_feed($feed_file);

                // parse the xml into an object
                $xml = \simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_COMPACT | LIBXML_PARSEHUGE);
            }
            catch (\Exception $ex)
            {
                $this->error($ex->getMessage());
                die;
            }

            foreach ($xml->branches->branch as $branch)
            {
                $branch_name = $this->parse($branch->attributes()->name);
                $this->info(sprintf('Branch: %s', $branch_name));

                // Reset counter for this branch
                $counter = 0;

                // $this->info(sprintf('%d. Branch "%s" has %d properties', ++$counter, $branch_name, $branch->properties->property->count()));

                $is_a_disallowed_branch = in_array($branch_name, $disallowed_branches);
                if ($is_a_disallowed_branch)
                {
                    $this->warn(sprintf('Ignoring disallowed branch "%s"', $branch_name));
                    continue;
                }

                foreach ($branch->properties->property as $listing)
                {
                    // Get unique ref
                    $ref = $this->parse($listing->attributes()->reference);

                    $this->info(sprintf('%d/%d. %s', ++$counter, $branch->properties->property->count(), $ref));

                    // Gather all the data we need
                    $prep = [];

                    // Anything to reject?

                    if (empty($ref))
                    {
                        // Reject
                        $this->warn(sprintf('Rejecting listing %d for not having a reference ID', $counter));
                        continue;
                    }

                    // Examples seen:
                    // ""
                    $prep['department'] = $this->parse($listing->department);

                    $prep['is_rental'] = (strpos($prep['department'], 'Lettings')) ? 1 : 0;
                    if ($prep['is_rental'])
                    {
                        $this->warn(sprintf('Rejecting listing ref. "%s" because it was flagged as a Rental ("%s")',
                            $ref,
                            $prep['department']
                        ));
                        continue;
                    }
                    //////////////////////////////////////////////////////////////////////////////////////

                    // Good chance blank country = a United Kingdom property
                    $prep['country'] = $this->parse($listing->country);
                    if (! $prep['country'] || $prep['country'] == 'UK')
                    {
                        $prep['country'] = 'United Kingdom';
                    }

                    if ($prep['country'] != 'United Kingdom')
                    {
                        // e.g. "FR" (seen in feed)
                        $this->warn(sprintf('Rejecting listing ref. "%s" because country is %s', $ref, $prep['country']));
                        continue;
                    }

                    $prep['postcode'] = $this->parse($listing->postcode);

                    $prep['property_status'] = $this->parse($listing->priority) ?: 'On Market';
                    switch ($prep['property_status'])
                    {
                        case 'On Market': $prep['property_status'] = 'Available'; break;
                        case 'Sold STC': $prep['property_status'] = 'SSTC'; break;
                    }
                    $prep['status'] = 0;
                    // "Properties marked exchanged should never appear on site." - client, 28/7/22
                    if ($prep['property_status'] === 'Exchanged')
                    {
                        // Reject
                        $this->warn(sprintf('Rejecting listing %s because of status "%s"', $ref, $prep['property_status']));
                        continue;
                    }

                    // No more rejections from this point, please

                    $prep['property_type'] = $this->parse($listing->property_type);

                    // Any types to remap?
                    $prep['property_type'] = $this->remap_property_type($prep['property_type']);

                    // Only expecting one property type per listing on the feed
                    $prep['property_type_id'] = array_search($prep['property_type'], $_property_types);

                    // Format on database: ",2,3,"
                    $prep['property_type_ids'] = '';

                    if (! $prep['property_type_id'])
                    {
                        $this->warn(sprintf('Property type not recognised: "%s"', $prep['property_type']));
                    }
                    else
                    {
                        $prep['property_type_ids'] = sprintf(',%s,', $prep['property_type_id']);
                    }

                    $prep['street'] = $this->parse($listing->street);
                    $prep['town'] = $this->parse($listing->district);
                    $prep['city'] = $this->parse($listing->town) ?: $this->parse($listing->area);
                    $prep['region'] = $this->parse($listing->county);
                    $prep['latitude'] = (float) $this->parse($listing->latitude);
                    $prep['longitude'] = (float) $this->parse($listing->longitude);
                    $prep['beds'] = (int) $this->parse($listing->bedrooms);
                    $prep['baths'] = (float) $this->parse($listing->bathrooms);
                    $prep['reception'] = (int) $this->parse($listing->receptions);
                    // $prep['propertyofweek'] = ($this->parse($listing->propertyofweek) === 'YES') ? 1 : 0;
                    // $prep['new_home'] = ($this->parse($listing->newHome) === 'YES') ? 1 : 0;
                    // $prep['no_chain'] = ($this->parse($listing->noChain) === 'YES') ? 1 : 0;
                    $prep['price'] = (int) $this->parse($listing->numeric_price); // GBP
                    // $prep['internal_area'] = ; // not seen
                    // $prep['land_area'] = ; // not seen

                    $prep['description'] = $this->parse($listing->main_advert);
                    $prep['description'] = '<p>'.nl2br($prep['description']).'</p>';

                    $prep['name'] = $this->parse($listing->advert_heading);
                    $prep['name'] = Str::limit($prep['name'], 77);

                    /*
                    $prep['availableFrom'] = $this->parse($listing->availableFrom);
                    if (! $prep['availableFrom']) $prep['availableFrom'] = null;
                    // Expect format: dd/mm/yyyy hh:mm:ss
                    if (strpos($prep['availableFrom'], '/'))
                    {
                        // We have a date to work with; instantiate with Carbon
                        $prep['availableFrom'] = str_replace('/', '-', $prep['availableFrom']);
                        $prep['availableFrom'] = new Carbon($prep['availableFrom']);
                    }
                    if (! $prep['availableFrom']) $prep['availableFrom'] = null;

                    $prep['instructedDate'] = $this->parse($listing->instructedDate);
                    if (! $prep['instructedDate']) $prep['instructedDate'] = null;
                    // Expect format: dd/mm/yyyy hh:mm:ss
                    if (strpos($prep['instructedDate'], '/'))
                    {
                        // We have a date to work with; instantiate with Carbon
                        $prep['instructedDate'] = str_replace('/', '-', $prep['instructedDate']);
                        $prep['instructedDate'] = new Carbon($prep['instructedDate']);
                    }
                    */

                    $prep['features'] = [];
                    $xpath = $listing->xpath('./*[starts-with(name(), "bullet")]');
                    if ($xpath)
                    {
                        foreach ($xpath as $node) {
                            $feature = $this->parse($node);
                            $feature = html_entity_decode($feature);
                            if (! $feature) continue;
                            $prep['features'][] = $feature;
                        }
                    }
                    $prep['add_info'] = implode(';', $prep['features']);

                    $prep['images'] = [];
                    if ($listing->pictures->picture)
                    {
                        foreach ($listing->pictures->picture as $photo)
                        {
                            $photo_url = $this->parse($photo->filename);
                            $photo_url = preg_replace('/^http\:/', 'https:', $photo_url); // Solves Mixed Content problem
                            $prep['images'][] = $photo_url;
                        }
                    }

                    $prep['floorplans'] = [];
                    if ($listing->floorplans->floorplan)
                    {
                        foreach ($listing->floorplans->floorplan as $floorplan)
                        {
                            $floorplan_url = $this->parse($floorplan->filename);
                            $floorplan_url = preg_replace('/^http\:/', 'https:', $floorplan_url); // Solves Mixed Content problem
                            $prep['floorplans'][] = $floorplan_url;
                        }
                    }

                    $prep['documents'] = [];
                    if ($_brochure = $this->parse($listing->brochure))
                    {
                        $prep['documents'][] = $_brochure;
                    }
                    if ($_epc = $this->parse($listing->epc))
                    {
                        $_epc = preg_replace('/^http\:/', 'https:', $_epc); // Solves Mixed Content problem
                        $prep['documents'][] = $_epc;
                    }
                    unset($_brochure, $_epc);

                    // Example seen:
                    // https://my.matterport.com/show/?m=....
                    $tour = $this->parse($listing->virtual_tour_url);
                    // <hips> (Home Information Pack) has YouTube and Vimeo URLs
                    // We want youtubes for "youtube_id"
                    $hips = $this->parse($listing->hips);
                    $prep['youtube_id'] = $this->get_youtube_id($hips);
                    // Catch out Vimeo URLs and ensure they're "safe":
                    // $tour = $this->remap_video_url($tour); // Do not do this anymore - just take what we get - RH 9/6/22
                    $prep['virtual_tour_url'] = $tour;
                    unset($tour);

                    $property = Property::where('ref', $ref)->withTrashed()->get()->first();
                    /*
                    $this->info(sprintf('Property ref "%s" %s exist%s',
                        $ref,
                        ($property ? 'already' : 'does not yet'),
                        ($property ? 's' : '')
                    ));
                    */
                    if (! $property) $property = new Property;

                    if (! $property->exists)
                    {
                        // It's a new property
                        // Populate "is_featured" on creation; never overwrite later
                        $prep['is_featured'] = ($this->parse($listing->featuredProperty) === 'YES') ? 1 : 0;
                        $property->is_featured = $prep['is_featured'];
                    }

                    $property->ref = $ref;
                    $property->add_info = $prep['add_info'];
                    // $property->available_from = $prep['availableFrom'];
                    $property->baths = $prep['baths'];
                    $property->beds = $prep['beds'];
                    $property->city = $prep['city'];
                    $property->country = $prep['country'];
                    $property->description = $prep['description'];
                    $property->feed_id = $feed_id; // ...the property now belongs to this feed
                    // $property->instructed_date = $prep['instructedDate'];
                    $property->is_rental = $prep['is_rental'];
                    $property->latitude = $prep['latitude'];
                    $property->longitude = $prep['longitude'];
                    $property->name = $prep['name'];
                    // $property->new_home = $prep['new_home'];
                    // $property->no_chain = $prep['no_chain'];
                    $property->postcode = $prep['postcode'];
                    $property->price = $prep['price'];
                    $property->property_status = $prep['property_status'];
                    $property->property_type_id = $prep['property_type_id'];
                    $property->property_type_ids = $prep['property_type_ids'];
                    // $property->propertyofweek = $prep['propertyofweek'];
                    $property->region = $prep['region'];
                    $property->status = $prep['status'];
                    $property->street = $prep['street'];
                    $property->town = $prep['town'];
                    $property->user_id = $user_id;
                    // $property->virtual_tour_url = $prep['virtual_tour_url'];
                    $property->youtube_id = $prep['youtube_id'];
                    // $property->agent_notes = $prep['agent_notes'];
                    // $property->branch_id = $branch_id;
                    // $property->internal_area = $prep['internal_area'];
                    // $property->internal_area_unit = $prep['internal_area_unit'];
                    // $property->land_area = $prep['land_area'];
                    // $property->land_area_unit = $prep['land_area_unit'];
                    $prep['is_rental'] && $property->rent_period = 3; // only populates if this is a Rental
                    $property->save();

                    // If property was previously archived, unarchive it
                    $property->trashed() && $property->restore();
                    $this->info(sprintf(' -> Property ID: %d', $property->id));

                    $refs[] = $ref;

                    $this->import_images($prep['images'], $property);
                    $this->import_images($prep['floorplans'], $property, 'floorplan');
                    $this->import_documents($prep['documents'], $property);
                }
            }

            // Delete from database old listings from this feed
            $refs_db = Property::where('feed_id', $feed_id)->get()->pluck('ref')->toArray();
            $refs_del = array_diff($refs_db, $refs);
            $counter = 0;
            $this->info(vsprintf('Saw %s different listing%s between feed (ID %d) and database', [
                number_format(count($refs_del)),
                (count($refs_del) === 1 ? '' : 's'),
                $feed_id
            ]));
            foreach ($refs_del as $ref_del)
            {
                $this->info(sprintf('%d/%d. Deleting ref %s', ++$counter, count($refs_del), $ref_del));
                $refs_db = Property::where('feed_id', $feed_id)->where('ref', $ref_del)->limit(1)->delete();
            }
        }
    }

    /**
     * All URLs in the feed are in this format:
     * https://youtu.be/1234567890a
     * https://vimeo.com/1234567890a
     * So it's easy to get that ID each time
     */
    private function get_youtube_id($url = null)
    {
        $youtube_id = null;
        if (empty($url)) return $youtube_id;
        // Only want YouTubes
        $url = rtrim($url, '/');
        if (preg_match('/youtu\.?be/', $url))
        {
            $_explode = explode('/', $url);
            $youtube_id = end($_explode);
        }
        return $youtube_id;
    }

    /**
     * Convert property type name as needed
     * @param  mixed $type
     * @return string
     */
    private function remap_property_type($type)
    {
        // The property types currently in the database may be wrong; I'll presume they are correct. RH
        switch ($type)
        {
            case '': // Default empties to "House".. my idea. Properties should have a type. RH
                $type = 'House';
                break;
            case 'Apartment / Studio':
            case 'Flat':
                $type = 'Apartment';
                break;
            case 'Bungalow':
                $type = 'House';
                break;
            case 'Character Property':
                $type = 'Character Features';
                break;
            /*
            case 'Houses':
                // Trim the 's' off the end
                $type = rtrim($type, 's');
                break;
            */
        }

        return $type;
    }

    private function fetch_feed($feed_file)
    {
        // tmp local cache
        if (app()->environment() === 'local')
        {
            $_path = public_path($feed_file);
            // if file exists, use it instead of remote feed
            if (is_readable($_path))
            {
                $response = file_get_contents($_path);
                $this->info(sprintf('Recalled feed %s from local disk', $_path));
                return $response;
            }
        }

        $host = config('expertagent.ftp.host');
        $user = config('expertagent.ftp.user');
        $pass = config('expertagent.ftp.pass');
        $curl_url = "ftp://$host/$feed_file";

        try
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $curl_url);
            curl_setopt($ch, CURLOPT_PORT, 21);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERPWD, "$user:$pass");
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $response = curl_exec($ch);
            curl_close($ch);
        }
        catch (\Exception $ex)
        {
            $this->error($ex->getMessage());
            exit;
        }

        return $response;
    }

}
