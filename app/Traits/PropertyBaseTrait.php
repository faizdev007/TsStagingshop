<?php

namespace App\Traits;
use App\Models\Category;
use App\Models\Community;
use App\Models\PropertyCategory;
use App\Models\PropertyCommunity;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Property;
use App\PropertyMedia;
use App\PropertyType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * @todo Refactor Code
 *
 */

trait PropertyBaseTrait
{
    use ImageTrait;

    public function import()
    {
        $feed_id = '1'; // Propertybase...

        // Set Page
        $page = '-1';

        // Store Property ID's in an Array....
        $feed_property_ids = [];
        $pb_ids = [];

        $num_rejected = 0;
        $num_allowed = 0;

        $start_time = Carbon::now();

        do
        {
            $page++;
            $this->info(sprintf('Page: %d', $page));

            $result = $this->get_property_feed_data($page);

            if (! $result)
            {
                // re-attempt
                $this->error('False result!');
                continue;
            }

            $data = json_decode($result);

            if(!$data->listings)
            {
                // No More Data..
                $this->error('No (more) data!');
                break;
            }

            $num_listings = $data->numberOfListings;

            // Output how many in feed.
            $this->info(sprintf('Number Of Properties in feed: %d', $num_listings));

            // Loop Through Listings...
            foreach($data->listings as $listing)
            {
                // PB ID....
                $pb_id = $listing->data->id;

                // Status..
                $status = trim($listing->data->pba__status__c);

                // Get Property Type...
                $property_type = $listing->data->pba__propertytype__c;

                if(!$property_type)
                {
                    $num_rejected++;

                    $this->warn(sprintf('- Property: %s has no Property Type Set', $pb_id));
                    continue;
                }

                // Prepare Images For Adding to DB....
                $images = $listing->media->images;

                if(empty($images))
                {
                    $this->info(' - No images set : '.$pb_id);

                    $num_rejected++;

                    continue;
                }

                $this->info(sprintf('- Property: %s', $pb_id));

                // Check if Property Exists...
                $property_exists = Property::withTrashed()->where('pb_id', trim($pb_id))->where('feed_id', $feed_id)->first();

                if($property_exists)
                {
                    // Restore Any Deleted Properties that may have re-appeared....
                    if($property_exists->trashed())
                    {
                        $restore = Property::withTrashed()->find($property_exists->id)->restore();
                    }

                    // Update The Property...
                    $property = $property_exists;
                    $this->info(sprintf('- In Database: %s', $property->id));
                    $import_media = true;
                    $property_is_new = false;
                }
                else
                {
                    // Create Property...
                    $this->info('- New Property');
                    $property = new Property;
                    $property_is_new = true;
                    $import_media = true;
                }

                $pb_ids[] = $pb_id;

                // Prepare Data...
                $property_data = array();

                // Get Listing Type (Sale Or Rent)...
                $listing_type = $listing->data->pba__listingtype__c;

                switch ($listing_type)
                {
                    case 'Residential Sale': // Sale
                        $property_data['is_rental'] = 0;
                    break;
                    case 'Residential Rent': // Rental
                        $property_data['is_rental'] = 1;
                        $property_data['rent_period'] = 3; // Monthly
                    break;
                    case 'Commercial Sale':
                        $property_data['is_rental'] = 0;
                    break;
                    case 'Commercial Rent':
                        $property_data['is_rental'] = 1;
                        $property_data['rent_period'] = 3; // Monthly
                        break;
                    default:
                        $property_data['is_rental'] = 0;
                        $property_data['rent_period'] = null;
                }

                // Set a Property Types Array, Which creates comma separated vals in the DB
                $property_types = array();

                // Communities...
                $property_data['communities'] = array();

                // Community Stuff....
                $community = $listing->data->location__c;
                $communities = explode(";", $community);

                // Do Some Replacements If Needed...
                $replace_community = array(
                    'Apes Hill Estate',
                    'Mullins Bay',
                    'Sugar Hill Tennis Resort',
                    'Royal Westmoreland Golf Resort'
                );

                foreach($communities as $community)
                {
                    if(in_array($community, $replace_community))
                    {
                        switch($community)
                        {
                            case "Apes Hill Estate":
                                $community = "Apes Hill";
                                break;
                            case "Mullins Bay":
                                $community = "Mullins";
                                break;
                            case "Sugar Hill Tennis Resort":
                                $community = "Sugar Hill Resort";
                                break;
                            case "Royal Westmoreland Golf Resort":
                                $community = "Royal Westmoreland";
                                break;
                        }
                    }

                    $community_exists = Community::findByName($community);

                    if($community_exists)
                    {
                        // Add it To Categories Array...
                        $property_data['communities'][] = $community_exists->id;
                    }
                }

                $property_data['categories'] = array();

                $skip_categories = array
                (
                    'Active Lifestyle',
                    'Beachfront',
                    'Luxury',
                    'Luxurious',
                    'Gated Community'
                );

                // Get "Category" Value....
                $category = $listing->data->property_sub_type_2__c;

                if($category)
                {
                    $categories = explode(";", $category);

                    // Add Commercial Property Category...
                    if($listing_type == 'Commercial Sale')
                    {
                        // Push Commercial Property to Array....
                        array_push($categories, "Commercial Property");
                    }

                    $land_type_id = PropertyType::where('name', 'Land')->pluck('id')->first();

                    foreach($categories as $cat)
                    {
                        // No Land / Commercial Properties in Collections or Categories...
                        if($property_type == 'Land' || $listing_type == 'Commercial')
                        {
                            // Don't Categorise...
                            if(in_array($cat, $skip_categories))
                            {
                                continue;
                            }
                        }

                        $property_types[] = $cat;

                        // Check If Category Exists
                        $category_exists = Category::where('name', $cat)->first();

                        if(!$category_exists)
                        {
                            // Create It
                            $new_category = new Category;
                            $new_category->name = $cat;
                            $new_category->save();

                            $property_data['categories'][] = $new_category->id;
                        }
                        else
                        {
                            // Add it To Categories Array...
                            $property_data['categories'][] = $category_exists->id;
                        }
                    }
                }

                // Manually Insert "Commercial Property"
                if($listing_type == 'Commercial Sale' || $listing_type == 'Commercial Rent')
                {
                    $category_name = 'Commercial Property';
                    $property_types[] = $category_name;

                    $category = Category::where('name', $category_name)->first();
                    if($category)
                    {
                        $property_data['categories'][] = $category->id;
                    }
                    else
                    {
                        // Create New Category...
                        $new_category = new Category;
                        $new_category->name = $category_name;
                        $new_category->save();

                        $property_data['categories'][] = $new_category->id;
                    }
                }

                // Manually Insert "Residential Property"
                if($listing_type == 'Residential Sale' || $listing_type == 'Residential Rent')
                {
                    if($property_type !== 'Land')
                    {
                        $category_name = 'Residential Properties';
                        $property_types[] = $category_name;

                        $category = Category::where('name', $category_name)->first();
                        if($category)
                        {
                            $property_data['categories'][] = $category->id;
                        }
                        else
                        {
                            // Create New Category...
                            $new_category = new Category;
                            $new_category->name = $category_name;
                            $new_category->save();

                            $property_data['categories'][] = $new_category->id;
                        }
                    }
                }

                if($listing_type == 'Residential Land')
                {
                    $category_name = "Residential Properties Land";
                    $property_types[] = $category_name;

                    $category = Category::where('name', $category_name)->first();
                    if($category)
                    {
                        $property_data['categories'][] = $category->id;
                    }
                    else
                    {
                        // Create New Category...
                        $new_category = new Category;
                        $new_category->name = $category_name;
                        $new_category->save();

                        $property_data['categories'][] = $new_category->id;
                    }
                }

                // See If Exists
                $property_type_exists = PropertyType::where('name', $property_type)->first();

                if(!$property_type_exists)
                {
                    $new_property_type = new PropertyType;
                    $new_property_type->name = $property_type;
                    $new_property_type->types = "property_type";
                    $new_property_type->save();

                    $property_data['property_type_id'] = $new_property_type->id;
                    $property_types[] = $new_property_type->id;
                }
                else
                {
                    $property_data['property_type_id'] = $property_type_exists->id;
                }

                $property_types[] = $property_type;

                if($property_types)
                {
                    $category_types = array('Residential Properties Land', 'Residential Properties', 'Commercial Property');

                    $property_types = array_unique($property_types);

                    $property_type_ids = array();

                    foreach($property_types as $property_type)
                    {
                        if(!is_numeric($property_type))
                        {
                            // See If Exists
                            $property_type_exists = PropertyType::where('name', $property_type)->first();

                            if(!$property_type_exists)
                            {
                                $new_property_type = new PropertyType;
                                $new_property_type->name = $property_type;
                                if(!in_array($property_type, $category_types))
                                {
                                    $new_property_type->types = "property_type";
                                }
                                else
                                {
                                    $new_property_type->types = "category";
                                }
                                $new_property_type->save();
                                $property_type_ids[] = $new_property_type->id;
                            }
                            else
                            {
                                $property_type_ids[] = $property_type_exists->id;
                            }
                        }
                    }
                }

                // Parish Switch...
                $region = $listing->data->pba__state_pb__c;
                switch($region)
                {
                    case "CC":
                        $parish = "Christ Church";
                    break;
                    case "PH":
                        $parish = "St. Phillip";
                    break;
                    case "MI":
                        $parish = "St. Michael";
                    break;
                    case "JM":
                        $parish = "St. James";
                    break;
                    case "PE":
                        $parish = "St. Peter";
                    break;
                    case "JS":
                        $parish = "St. Joseph";
                    break;
                    case "TH":
                        $parish = "St. Thomas";
                    break;
                    case "GE":
                        $parish = "St. George";
                    break;
                    case "LU":
                        $parish = "St. Lucy";
                    break;
                    case "JN":
                        $paris = "St. John";
                    break;
                    default:
                        $parish = $listing->data->pba__state_pb__c;
                    break;
                }

                $property_data['parish'] = $parish;

                // Default User...
                $property_data['user_id'] = 0; // Default...

                // Set Floorplans Array....
                $property_data['floorplans'] = array();

                // Set Images Array..
                $property_data['images'] = array();

                foreach($images as $image)
                {
                    $tags = strtolower($image->tags);

                    $filename = str_replace(' ', '%20', $image->filename);
                    $filename = str_replace('#', '%23', $image->filename);

                    if($tags)
                    {
                        if($tags == 'floorplan')
                        {
                            // Floorplan...
                            $property_data['floorplans'][] = $image->url;
                        }
                        else
                        {
                            // Image...
                            $property_data['images'][] = $image->baseurl.'/midres/'.$filename;
                        }
                    }
                    else
                    {
                        // Image...
                        $property_data['images'][] = $image->baseurl.'/midres/'.$filename;
                    }
                }

                // Documents...
                $documents = $listing->media->documents;

                // END Property Data Prep....
                $property->user_id = 1; // Default...
                $property->feed_id = $feed_id; // Propertybase
                $property->property_type_id = $property_data['property_type_id']; // Set Further Up (Creating new if not exists)...
                if($property_type_ids)
                {
                    $property_types = array_unique($property_type_ids);

                    $property_type_string = ',';
                    $property_type_string.= implode(",", $property_type_ids);
                    $property_type_string .= ',';

                    $property->property_type_ids = $property_type_string;
                }
                $property->pb_id = $pb_id;
                //$property->ref = $ref; // Ref is PB Ref...
                $property->status = 0; // Active
                $property->is_rental = $property_data['is_rental']; // Rent or Not

                $is_featured = $listing->data->pick_of_the_month__c;
                if($is_featured)
                {
                    $property->is_featured = 1;
                }
                else
                {
                    $property->is_featured = NULL;
                }

                $property->street = $listing->data->pba__address_pb__c; // Street Address
                $property->city = $listing->data->pba__city_pb__c; // May Need to be Town?
                $property->region = $property_data['parish'];
                $property->postcode = $listing->data->pba__postalcode_pb__c; // Postcode
                $property->country = 'Barbados';
                if($listing->data->pba__latitude_pb__c)
                {
                    if($this->is_decimal($listing->data->pba__latitude_pb__c))
                    {
                        $property->latitude = str_replace(',', '.', $listing->data->pba__latitude_pb__c); // Lat
                    }
                }

                if($listing->data->pba__longitude_pb__c)
                {
                    if($this->is_decimal($listing->data->pba__longitude_pb__c))
                    {
                        $property->longitude = str_replace(',','.', $listing->data->pba__longitude_pb__c); // Long
                    }
                }

                $property_data['price'] = intval(round($listing->data->pba__listingprice_pb__c)); // Price
                $property_data['price_min'] = intval(round($listing->data->listing_price_minimum__c)); // Min Price
                $property_data['price_max'] = intval(round($listing->data->listing_price_maximum__c)); // Max Price
                // Don't use min./max.price on rentals - RH 4/5/2021
                if (! $property_data['is_rental'])
                {
                    // If no price, check/use min.price or then check/use max.price
                    if (! $property_data['price'])
                    {
                        if ($property_data['price_min'])
                            $property_data['price'] = $property_data['price_min'];
                        elseif ($property_data['price_max'])
                            $property_data['price'] = $property_data['price_max'];
                    }

                    // Don't allow price to be less than price_min
                    if ($property_data['price_min'] && $property_data['price'] < $property_data['price_min'])
                    {
                        $property_data['price'] = $property_data['price_min'];
                    }
                }

                $property->price = $property_data['price'];
                // Don't use min./max.price on rentals - RH 4/5/2021
                if (! $property_data['is_rental'])
                {
                    $property->price_min = $property_data['price_min'];
                    $property->price_max = $property_data['price_max'];
                }

                $property->beds = intval(round($listing->data->pba__bedrooms_pb__c)); // Beds
                $property->baths = intval(round($listing->data->pba__fullbathrooms_pb__c)); // Baths...
                $property->property_status = $listing->data->pba__status__c; // Status...
                $property->internal_area = intval(round($listing->data->pba__totalarea_pb__c));
                $property->land_area = intval(round($listing->data->pba__lotsize_pb__c));
                $property->description = $listing->data->pba__description_pb__c;
                $property->name = $listing->data->name;

                // Custom Fields (ADD HERE).....
                $amenities = str_replace(';', ',', $listing->data->amenities__c);
                $property->add_amenities = $amenities;

                $key_features = str_replace(';', ',', $listing->data->key_features__c);
                if($key_features)
                {
                    $property->add_info = $key_features;
                }

                $property->save();

                if($property_data['categories'])
                {
                    $this->set_property_categories($property_data['categories'], $property);
                }

                if($property_data['communities'])
                {
                    $this->set_property_communities($property_data['communities'], $property);
                }

                if ($property_is_new)
                {
                    $this->info(' - now in database: ID '.$property->id);
                }

                // Add Reference #....
                $property_update = Property::find($property->id);
                $ref_prefix = settings('ref_prefix');
                $property_update->ref = $ref_prefix.$property->id;
                $property_update->save();

                // Import Images
                if($import_media)
                {
                    $this->import_images($property_data['images'], $property);
                    $this->import_images($property_data['floorplans'], $property, 'floorplan');

                    // Import Docs..
                    $this->import_documents($documents, $property);
                }
            }

        } while ($page < 10);

        // Delete any Old Properties; get refs or ids for existing properties...
        $live_property_ids = Property::where('feed_id', $feed_id)->pluck('pb_id')->toArray();

        $delete_property_ids = [];

        foreach($live_property_ids as $live_property_id)
        {
            if(!in_array($live_property_id, $pb_ids))
            {
                $data = Property::where('feed_id', $feed_id)->where('pb_id', $live_property_id)->first();

                $delete_property_ids[] = $data->id;
            }
        }

        $this->info(sprintf('%d properties in feed', ($num_listings),($num_listings == 1 ? '' : 's')));
        $this->info(sprintf('%d Rejected Properties', ($num_rejected)));
        $this->info(sprintf('%d property ID%s in Database', count($live_property_ids),($live_property_ids == 1 ? '' : 's')));
        $this->info('Start Time: '. $start_time);
        $this->info('End Time: '. Carbon::now());
        $diff = $start_time->diffInRealMinutes(Carbon::now());
        $this->info('Runtime (Minutes) : '. $diff);

        // Delete The Differences...
        $deleted = 0;
        if ($delete_property_ids)
        {
            foreach ($delete_property_ids as $delete_property_id)
            {
                $this->info('Deleting Property' . $delete_property_id);
                $deleted++;

                $property = Property::find($delete_property_id);
                $property->delete();
            }
        }

        $this->info(sprintf('Deleted : %s properties', $deleted));

        $this->info("Complete.");
    }

    function set_property_communities($communities = [], $property)
    {
        $current_communities = PropertyCommunity::where('property_id', $property->id)->get();

        if($current_communities)
        {
            $compare_array = $communities;

            // Compare (If Differences - Add New Category)...
            $existing_array = PropertyCommunity::where('property_id', $property->id)->pluck('community_id')->toArray();

            // First Compare Keys...
            $differences = array_diff($existing_array, $compare_array);
            //$other_diff = array_diff($existing_array, $compare_array);

            // Also Compare Order
            $order_check = array_diff($compare_array,array_intersect_assoc($existing_array, $compare_array));

            if($differences || $order_check)
            {
                // Clear Existing...
                foreach($current_communities as $current_community)
                {
                    $remove_community = PropertyCommunity::find($current_community->id);
                    $remove_community->delete();
                }

                // Create New Pivot Values...
                foreach($communities as $community)
                {
                    $new_community = new PropertyCommunity;
                    $new_community->property_id = $property->id;
                    $new_community->community_id = $community;
                    $new_community->save();
                }
            }
        }
        else
        {
            // Create New Pivot Values...
            foreach($communities as $community)
            {
                $new_community = new PropertyCommunity;
                $new_community->property_id = $property->id;
                $new_community->community_id = $community;
                $new_community->save();
            }
        }
    }

    function set_property_categories($categories = [], $property)
    {
        $current_categories = PropertyCategory::where('property_id', $property->id)->get();

        if($current_categories)
        {
            $compare_array = $categories;

            // Compare (If Differences - Add New Category)...
            $existing_array = PropertyCategory::where('property_id', $property->id)->pluck('category_id')->toArray();

            // First Compare Keys...
            $differences = array_diff_key($compare_array, $existing_array);

            if($differences)
            {
                // Clear Existing...
                foreach($current_categories as $current_category)
                {
                    $delete_category = PropertyCategory::find($current_category->id);
                    if($delete_category)
                    {
                        $delete_category->delete();
                    }
                }

                // Next Save New Pivots...
                foreach($categories as $category)
                {
                    $new_category = new PropertyCategory;
                    $new_category->property_id = $property->id;
                    $new_category->category_id = $category;
                    $new_category->save();
                }
            }

        }
        else
        {
            // Create New Pivot Values...
            foreach($categories as $category)
            {
                $new_category = new PropertyCategory;
                $new_category->property_id = $property->id;
                $new_category->category_id = $category;
                $new_category->save();
            }
        }
    }

    function import_documents($documents = [], $property)
    {
        if(!$documents) return;

        $document_counter = 0;
        $type = "document";

        if($property->propertyMediaDocuments->count())
        {
            // Compare
            $existing_array = $property->propertyMediaDocuments->pluck('path')->toArray();

            // Compare Array.
            $compare_array = array();

            foreach($documents as $document)
            {
                $compare_array[] = $document->url;
            }

            // Compare Keys...
            $differences = array_diff_key($compare_array, $existing_array);

            // Also Compare Values
            $equal = $this->array_equal($compare_array, $existing_array);

            if($differences || $equal == false)
            {
                $this->info(sprintf('New %s For Property - %s', $type, $property->id));

                // Clear Existing...
                foreach($property->propertyMediaDocuments as $PropertyMedia_id)
                {
                    $doc = PropertyMedia::find($PropertyMedia_id->id);

                    if (! empty($doc->path))
                    {
                        $doc->delete();
                    }
                }

                // Create New Documents...
                foreach($documents as $document)
                {
                    $document_counter++;

                    // Add New Images....
                    $file = new PropertyMedia;
                    $file->property_id = $property->id;
                    $file->type = $type;
                    $file->title = $document->title;
                    $file->path = $document->url;
                    $file->sequence = $document_counter;
                    $file->save();

                    $this->info(sprintf('Saved document %s', $document->url));
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            // Create New Documents...
            foreach($documents as $document)
            {
                $document_counter++;

                // Add New Images....
                $file = new PropertyMedia;
                $file->property_id = $property->id;
                $file->type = $type;
                $file->title = $document->title;
                $file->path = $document->url;
                $file->sequence = $document_counter;
                $file->save();

                $this->info(sprintf('Saved document %s', $document->url));
            }
        }
    }

    /**
     * @param array $images
     * @param $property
     * @param string $type
     * NOTE: We are only storing the images in our DB, Not on our Hosting, The PB Images are on an S3 Bucket...
     */

    function import_images($images = [], $property, $type = 'photo')
    {
        if(env('APP_ENV') == 'local')
        {
            $this->info('Skipping - Local Env');
            return;
        }

        if(! $images) return; // No Images to Import...

        $image_counter = 0;
        $mediaType = 'propertyMediaPhotos';
        if ($type === 'floorplan') $mediaType = 'propertyMediaFloorplans';

        if ($property->$mediaType->count())
        {
            // Compare & Update...
            $existing_array = PropertyMedia::where('property_id', $property->id)
                ->where('type', $type)
                ->orderBy('sequence', 'asc')
                ->pluck('path')
                ->toArray();

            $compare_array = $images;

            $differences = array_diff_assoc($compare_array, $existing_array);
            $differences2 = array_diff_assoc($existing_array, $compare_array);
            if($differences || $differences2)
            {
                $this->info(sprintf('New %s For Property - %s', $type, $property->id));

                $new_sequence = 0;

                // Clear Existing
                foreach ($property->$mediaType as $PropertyMedia_id)
                {
                    $photo = PropertyMedia::find($PropertyMedia_id->id);

                    if (! empty($photo->path))
                    {
                        $image_counter++;
                        $photo->delete();
                    }
                }

                foreach($images as $image)
                {
                    $new_sequence ++;

                    // Add New Images....
                    $photo = new PropertyMedia;
                    $photo->property_id = $property->id;

                    $photo->path = $image;
                    $photo->sequence = $new_sequence;
                    $photo->type = $type;
                    $photo->orientation = $this->get_image_orientation($image);
                    $photo->save();

                    $this->info(sprintf('Saved %s %d: %s', $type, $photo->sequence, $photo->path));
                }
            }
            else
            {
                return;
            }
        }
        else
        {
            // Create New Images...
            foreach($images as  $image)
            {
                $image_counter++;

                $photo = new PropertyMedia;
                $photo->property_id = $property->id;

                if ($type === 'floorplan')
                {
                    $photo->type = 'floorplan';
                }
                else
                {
                    $photo->type = 'photo';
                }

                $photo->path = $image;
                $photo->sequence = $image_counter;
                $photo->orientation = $this->get_image_orientation($image);
                $photo->save();

                $this->info(sprintf('Saved %s %d: %s', $type, $photo->sequence, $photo->path));
            }
        }

    }

    public function get_property_feed_data($page = 0)
    {
        $feed_url = $this->get_property_feed_url($page);

        // Use Guzzle
        $client = new Client();
        $response = $client->request('GET', $feed_url, ['verify' => false]);
        $result = $response->getBody();

        return $result;
    }

    public function get_property_feed_url($page = 0)
    {
        $url = config('propertybase.endpoint');
        $base_fields = config('propertybase.fields');
        $custom_fields = config('propertybase.custom');

        $fields = array_merge($base_fields, $custom_fields);

        $data = [
            'token' => config('propertybase.token'),
            'fields' => implode(';',$fields),
            'format' => config('propertybase.format'),
            'debugmode' => config('propertybase.debugmode'),
            'getDocuments' => config('propertybase.getDocuments'),
            'getImages' => config('propertybase.getImages'),
            'pba__SystemAllowedForPortals__c' => "true",
            'pba__listingtype__c' => 'IN(Residential Sale;Residential Rent;Commercial Sale;Commercial Rent;Residential Land)',
            //'pba__ListingPrice_pb__c' => '[0;]', // Filter
            //'id'    => 'a0E3i000006L2rIEAS',
            'pba__Status__c' => 'IN(Active;Pending;Under Offer;Sold)',
            'itemsPerPage' => 200,
            'addlinebreaks' => 'true',
            'page' => $page,
        ];

        $query_str = http_build_query($data);
        $url .= '?' . $query_str;

        return $url;
    }


    public function base_url($method = '')
    {
        $org_id = settings('propertybase_org');

        $url = 'https://integrations-api.propertybase.com/api/v1/messages/'.$org_id.'';

        return $url;
    }

    /**
     * @param $data
     * @param bool $user_id
     * @param string $action_type
     * Create the Lead in Propertybase - By Default Assumes it's a new lead, Will need to set an action type = 'edit' for Updates....
     */

    public function post_lead($data, $user_id = false, $action_type = 'create')
    {
        // Create Lead Array...

        // Manage Landing Page
        if(isset($data['url']))
        {
            $landing_page = $data['url'];
        }
        else
        {
            $landing_page = env('app_url');
        }


        $lead_fields = array
        (
            "object_type"       => "Lead",
            "action_type"       => $action_type,
            "data"  => [
                'id'                => $user_id,
                'first_name'        => $data['first_name'],
                'last_name'         => $data['surname'],
                'email'             => $data['email'],
                'phone1'            => (isset($data['telephone'])) ? $data['telephone'] : '',
                'landing_page'      => $landing_page,
                'referral_source'   => env('app_url'),
                'lead_status'       => 'new_lead',
                'comments'          => (isset($data['message'])) ? $data['message'] : ''
            ]
        );

        // Set RequestType....
        $request_type = 'post';

        // Get the Endpoint...
        $endpoint = $this->base_url('messages');

        $this->post_data( $endpoint, $request_type, $lead_fields);
    }

    /**
     * @param $data
     * @param string $action_type
     */

    public function post_newsletter($data, $action_type = 'create')
    {
        // Create Lead Array...
        $lead_fields = array
        (
            "object_type"       => "Lead",
            "action_type"       => $action_type,
            "data"  => [
                'id'                    => mt_rand(1,50000),
                'first_name'            => 'Unknown',
                'last_name'             => 'Unknown',
                'email'                 => $data['email'],
                'referral_source'       => env('app_url'),
                'lead_status'           => 'new_lead',
                'lead_source_category'  => 'Newsletter Signup'
            ]
        );

        // Set RequestType....
        $request_type = 'post';

        // Get the Endpoint...
        $endpoint = $this->base_url('messages');

        $this->post_data( $endpoint, $request_type, $lead_fields);
    }

    function array_equal($a, $b)
    {
        return
        (
            is_array($a)
            && is_array($b)
            && count($a) == count($b)
            && array_diff($a, $b) === array_diff($b, $a)
        );
    }

    function array_diff_order( $array1, $array2 )
    {
        $out = '';
        while ((list($key1, $val1) = each($array1)) && (list($key2, $val2) = each($array2)) ) {
            if($key1 != $key2 || $val1 != $val2) $out .= "-    $key1 => '$val1' \n+    $key2 => '$val2'\n";
        }
        return $out;
    }

    /**
     * @param $property
     * @param bool $user_id
     * @param string $action_type
     */

    public function post_property_view($id, $property, $user_id = '', $action_type = 'create')
    {
        $lead_fields = array(
            "object_type"   => "PropertyView",
            "action_type"   => $action_type,
            "data"  => [
                'id'                => $id,
                'property_id'       => $property->ref,
                'created_at'        => Carbon::now()->format('Y-m-d\TH:i:s'), //yyyy-MM-ddTHH:mm:ss
                'property_address'  => $property->display_address,
                'lead_id'           => $user_id,
                'property' => [
                    'town_name' => $property->city,
                    'price'     => $property->price,
                    'zip'       => $property->postcode
                ]
            ]
        );

        // Set Request Type
        $request_type = 'post';

        // Get The End Point
        $endpoint = $this->base_url('messages');

        $this->post_data( $endpoint, $request_type, $lead_fields);
    }

    /**
     * @param $enquiry
     * @param $property
     * @param string $user_id
     * @param string $action_type
     */

    public function post_inquiry($enquiry, $property, $user_id = '', $action_type = 'create')
    {
        $fields = array(
            "object_type"   => "Inquiry",
            "action_type"   => $action_type,
            "data"  => [
                'id'                => $enquiry->id,
                'property_address'  => $property->display_address,
                'property_id'       => $property->ref,
                'comments'          => $enquiry->message,
                'created_at'        => Carbon::now()->format('Y-m-d\TH:i:s'), //yyyy-MM-ddTHH:mm:ss
                'lead_id'           => $user_id,
                'property' => [
                    'town_name' => $property->city,
                    'price'     => $property->price,
                    'zip'       => $property->postcode
                ]
            ]
        );

        // Set Request Type
        $request_type = 'post';

        // Get The End Point
        $endpoint = $this->base_url('messages');

        $this->post_data( $endpoint, $request_type, $fields);
    }

    /**
     * @param $id
     * @param $enquiry_data
     * @param $user_id
     * @param string $action_type
     */

    public function post_contact_form($enquiry_data, $user_id, $action_type = 'create')
    {
        $lead_fields = array(
            "object_type"       => "ContactForm",
            "action_type"       => $action_type,
            "data"  => [
                "id"            => $enquiry_data->id,
                "lead_id"       => $user_id,
                "created_at"    => Carbon::now()->format('Y-m-d\TH:i:s'),
                "comments"      => $enquiry_data->message
            ]
        );

        // Set Request Type
        $request_type = 'post';

        // Get The End Point
        $endpoint = $this->base_url('messages');

        $this->post_data( $endpoint, $request_type, $lead_fields);
    }

    /**
     * @param $attributes
     * @param $user_id
     * @param string $action_type
     */

    public function post_saved_search($id, $attributes, $user_id, $action_type = 'create', $active = 'true')
    {
        // Do Something to Split Price (Min - Max)...
        $price_range = (!empty($attributes['price_range'])) ? explode("-", $attributes['price_range']) : '';

        $lead_fields = array(
            "object_type"   => "SavedSearch",
            "action_type"   => $action_type,
            "data"  => [
                'id'        => $id,
                'active'    => $active,
                'lead_id'   => $user_id,
                'search_attributes' => [
                    'towns'             => (!empty($attributes['in'])) ? $attributes['in'] : '',
                    'min_beds'          => (!empty($attributes['beds'])) ? $attributes['beds'] : '',
                    'property_types'    => [(!empty($attributes['property_type'])) ? $attributes['property_type'] : ''],
                    'max_beds'          => (!empty($attributes['beds'])) ? $attributes['beds'] : '',
                    'min_baths'         => (!empty($attributes['baths'])) ? $attributes['baths'] : '',
                    'max_baths'         => (!empty($attributes['baths'])) ? $attributes['baths'] : '',
                    'min_price'         => (isset($price_range) && !empty($price_range)) ? $price_range[0] : '',
                    'max_price'         => (isset($price_range) && !empty($price_range)) ? $price_range[1] : '',
                ]
            ]
        );

        // Set Request Type
        $request_type = 'post';

        // Get The End Point
        $endpoint = $this->base_url('messages');

        $this->post_data( $endpoint, $request_type, $lead_fields);
    }

    function is_decimal( $val )
    {
        return is_numeric( $val ) && floor( $val ) != $val;
    }

    /**
     * @param bool $endpoint
     * @param $request_type
     * @param $data
     */

    public function post_data($endpoint = false, $request_type, $data)
    {
        $token = settings('propertybase_token');

        $client = new Client(
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token, // Send Token...
                    'Content-Type'  => 'application/json'
                ]
            ]
        );

        if($request_type == 'post')
        {
            // Always Posting JSON Data...
            $response = $client->post($endpoint,
                [
                    'body' => json_encode($data)
                ]
            );

            $status_code = $response->getStatusCode();

            if($status_code == '201')
            {
                Log::info('Saved '.$data['object_type'].' to Propertybase! Data = '.print_r($data, true).'');
            }
            else
            {
                Log::warning('Propertybase Error - Check PB Logs');
            }
        }
    }

}
