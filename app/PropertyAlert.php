<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PropertyAlert extends Model
{
    // Table name
    protected $table = 'property_alerts';

    // Primary key
    public $primaryKey = 'id';

    //Timestamps
    public $timestamps = true;

    /**
     * Get the property type record associated with the property alerts
     *
     */
    public function property_type()
    {
        return $this->belongsTo('App\PropertyType', 'property_type_id', 'id');
    }

    /**
     * Accessor:
     * The headline for search results, Similar Properties, Featured Properties etc
     *
     */
    public function getDisplayStatusAttribute()
    {
        $value = ($this->is_active == 1) ? 'Active' : 'In-active';
        return $value;
    }

    public function getDisplayModeAttribute()
    {
        $value = ($this->is_rental == 1) ? 'Rent' : 'Sale';
        return $value;
    }

    /**
     * Accessor:
     * The headline for Property details
     *
     */
    public function getDetailsHeadlineAttribute()
    {
        // format: [NUMBER OF BEDS] [PROPERTY TYPE] [FOR SALE / TO RENT] in [REGION]

        $items = [];
        if ($this->beds) $items[] = $this->beds.' Bed';
        if($this->DisplayPropertyType)
        {
            $items[] = Str::plural($this->DisplayPropertyType);
        }
        else
        {
            $items[] = "Properties";
        }

        /*
        if(!empty($this->price_from && !empty($this->price_to)))
        {
            $items[] = 'priced : '. settings('currency_symbol'). $this->price_from .' - '. settings('currency_symbol'). $this->price_to;
        }
        */

        if(!empty($this->price_from ))
        {
            $items[] = 'Min Price : '. settings('currency_symbol').thousandscurrencyformat($this->price_from);
        }

        if( !empty($this->price_to)
        )
        {
            $items[] = 'Max Price : '. settings('currency_symbol').thousandscurrencyformat($this->price_to);
        }

        $items[] = ($this->is_rental) ? 'To Rent' : 'For Sale';
        $items[] = ($this->in) ? 'In '. $this->in : '';
        $value = implode(' ', $items);

        $value = !empty($this->name) ? str_replace("+", " ", $this->in) : str_replace("+", " ", $value);
        $value = urldecode($value);

        return $value;
    }

    /**
     * Accessor:
     * Display property type
     *
     */
    public function getDisplayPropertyTypeAttribute()
    {
        $value = (!empty($this->property_type)) ? $this->property_type->name : '';
        return $value;
    }

    /**
     * Accessor:
     * Display price range
     *
     */
    public function getPriceRangeAttribute()
    {
        $price_from = !empty($this->price_from) ? $this->price_from : 0;
        $price_to = !empty($this->price_to) ? $this->price_to : 0;
        $price_range = $price_from.'-'.$price_to;
        if($price_from == 0 && $price_to==0){
            $price_range = '';
        }
        return $price_range;
    }

    public function getDisplayPropertyTypesAttribute()
    {
        $ptypeArray = get_ptype();
        $property_type_ids = $this->property_type_ids;
        $propertyTypeLabel = [];
        $property_types = "";
        if(!empty($property_type_ids)){

            $property_type_ids_array = explode(',',$property_type_ids);

            if(count($property_type_ids_array)){
                foreach($property_type_ids_array as $property_type_id){
                    if (array_key_exists($property_type_id,$ptypeArray)){
                        $propertyTypeLabel[] = $ptypeArray[$property_type_id];
                    }
                }
            }
            if(count($propertyTypeLabel)){
                $property_types = implode(', ',$propertyTypeLabel);
            }
        }

        return $property_types;
    }



}
