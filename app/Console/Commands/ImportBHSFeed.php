<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Property;
use App\PropertyType;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Traits\PropertyBaseTrait;

class ImportBHSFeed extends Command
{
    use PropertyBaseTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:import_bhs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports BHS Feed';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * One Time Import of BHS Feed....
     *
     * @return mixed
     */
    public function handle()
    {
        $file = Storage::get('feeds/bhs-worldwide-nyc.xml');

        $data = simplexml_load_string($file);

        $this->info('Importing BHS Feed');

        $num_rejected = 0;
        $imported = 0;
        $feed_id = 2;
        $feed_refs = [];

        foreach($data as $listing)
        {
            $property = new Property;

            // Ref...
            $ref = trim($listing->ListingDetails->MlsId);

            $this->info(printf('- Property: %s', $ref, PHP_EOL));

            $property_exists = Property::withTrashed()->where('ref', $ref)->where('feed_id', $feed_id)->first();

            if($property_exists)
            {
                // Restore Any Deleted Properties that may have re-appeared....
                if($property_exists->trashed())
                {
                    $restore = Property::withTrashed()->find($property_exists->id)->restore();
                }

                // Update The Property...
                $property = $property_exists;
                $this->info(printf('- In Database: %s', $property->id, PHP_EOL));
                $import_media = true;
                $property_is_new = false;
            }
            else
            {
                // Create Property...
                $this->info(printf('- New Property', PHP_EOL));
                $property = new Property;
                $property_is_new = true;
                $import_media = true;
            }

            // Prepare Images For Adding to DB....
            $images = $listing->Pictures;

            $feed_refs[] = $ref;

            // Prepare Data...
            $property_data = array();

            // Assuming BHS is SALE Only - Not seen any rentals..
            $property_data['is_rental'] = 0;
            $property_data['rent_period'] = null;

            // Get Property Type...
            $property_type = trim($listing->BasicDetails->PropertyType);

            // See If Exists
            $property_type_exists = PropertyType::where('name', $property_type)->first();

            if(!$property_type_exists)
            {
                $new_property_type = new PropertyType;
                $new_property_type->name = $property_type;
                $new_property_type->save();

                $property_data['property_type_id'] = $new_property_type->id;
            }
            else
            {
                $property_data['property_type_id'] = $property_type_exists->id;
            }

            $cat = "Brown Harris Stevens";

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

            // Set Images Array..
            $property_data['images'] = array();

            foreach($images as $image)
            {
                // Image...
                $property_data['images'][] = trim($image->Picture->PictureUrl);
            }

            $property->user_id = 1; // Default...
            $property->feed_id = $feed_id; // Propertybase
            $property->property_type_id = $property_data['property_type_id']; // Set Further Up (Creating new if not exists)...
            $property->ref = $ref;
            $property->status = 0; // Active
            $property->is_rental = $property_data['is_rental']; // Rent or Not
            $property->property_status = $listing->ListingDetails->Status;
            $property->street = $listing->Location->StreetAddress; // Street Address
            $property->city = $listing->Location->City;
            $property->region = $listing->Location->StateProvince;
            $property->postcode = $listing->Location->Zip;
            $property->country = $listing->Location->Country;

            // No Lat / Lng at Present

            $property->price = intval(round($listing->ListingDetails->Price)); // Price
            $property->beds = intval(round($listing->BasicDetails->Bedrooms)); // Beds
            $property->baths = intval(round($listing->BasicDetails->Bathrooms)); // Baths...

            $description = trim($listing->BasicDetails->Description);
            $description.= "<br /><br />";
            $description.= "<strong>".$listing->Agent1->FirstName .' '. $listing->Agent1->LastName."</strong>";
            $description.= "<br />";
            $description.= $listing->Agent1->EmailAddress;
            $description.= "<br />";
            $description.= $listing->Agent1->CompanyName;

            $property->description = $description;
            $property->name = $listing->BasicDetails->Title;

            $property->save();

            if ($property_is_new)
            {
                $this->info(' - now in database: ID '.$property->id);
            }

            // Import Images
            if($import_media)
            {
                $this->import_images($property_data['images'], $property);
            }

            if($property_data['categories'])
            {
                $this->set_property_categories($property_data['categories'], $property);
            }

            $imported++;
        }

        // Delete any Old Properties; get refs or ids for existing properties...
        $live_property_ids = Property::where('feed_id', $feed_id)->pluck('ref')->toArray();

        $delete_property_ids = [];

        foreach($live_property_ids as $live_property_id)
        {
            if(!in_array($live_property_id, $feed_refs))
            {
                $data = Property::where('feed_id', $feed_id)->where('ref', $live_property_id)->first();

                $delete_property_ids[] = $data->id;
            }
        }

        $this->info(sprintf('Imported %d properties', ($imported)));
    }
}
