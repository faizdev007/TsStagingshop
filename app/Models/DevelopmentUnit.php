<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class DevelopmentUnit extends Model
{
    use SoftDeletes;

    protected $table = 'development_units';
    protected $primaryKey = 'development_unit_id';

    public function development()
    {
        return $this->hasOne('App\Models\Development', 'development_id', 'development_id');
    }

    public function property_type()
    {
        return $this->hasOne('App\PropertyType', 'id', 'property_type_id');
    }

    public function media()
    {
        return $this->hasMany('App\Models\DevelopmentUnitMedia', 'unit_id', 'development_unit_id');
    }

    public function getPrimaryPhotoAttribute()
    {
        $propertyMediaPhotos = $this->media;

        if( count($propertyMediaPhotos) )
        {
            if(settings('store_s3') !== 'false')
            {
                // Returns Display Image from Both S3 / Local If Dual Image Hosting Setup...
                $exists = Storage::disk('s3')->has($propertyMediaPhotos[0]->path);

                if($exists)
                {
                    $path = Storage::cloud()->url($propertyMediaPhotos[0]->path);
                }
                else
                {
                    $path = (Storage::exists($propertyMediaPhotos[0]->path)) ? storage_url($propertyMediaPhotos[0]->path) : default_thumbnail();
                }
            }
            else
            {
                if(settings('propertybase'))
                {
                    // If Propertybase, Load the URL String....
                    $path = $propertyMediaPhotos[0]->path;
                }
                else
                {
                    $path = (Storage::exists($propertyMediaPhotos[0]->path)) ? storage_url($propertyMediaPhotos[0]->path) : default_thumbnail();
                }
            }
        }
        else
        {
            $path = default_thumbnail();
        }

        return $path;
    }

    public function getDisplayPriceAttribute()
    {

        $currency_symbol=settings('currency_symbol');
        $currency_symbol = (!empty($currency_symbol)) ? $currency_symbol : '&pound;';

        if(settings('price_format') == '1')
        {
            $priceFormatted = pw_price_format($this->development_unit_price,'.');
        }
        else
        {
            $priceFormatted = number_format($this->development_unit_price);
        }

        $value = sprintf('%s%s',
            settings('currency_symbol'),
            $priceFormatted
        );

        return $value;
    }
}
