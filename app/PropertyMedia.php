<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PropertyMedia extends Model
{
    // Table name
    protected $table = 'property_media';

    // Primary key
    public $primaryKey = 'id';

    //Timestamps
    public $timestamps = true;

    public function property()
    {
        return $this->belongsTo('App\User');
    }

    public function getDisplayTitleAttribute()
    {
        return (!empty($this->title))? $this->title : $this->filename;
    }

    /**
     * Accessor:
     * Get the photo display
     *
     */
    public function getPhotoDisplayAttribute()
    {
        if(settings('store_s3') !== 'false')
        {
            // Returns Display Image from Both S3 / Local If Dual Image Hosting Setup...
            $exists = Storage::disk('s3')->exists($this->path);

            if($exists)
            {
                $path = Storage::cloud()->url($this->path);
            }
            else
            {
                $path = (Storage::exists($this->path)) ? storage_url($this->path) : default_thumbnail();
            }
        }
        else
        {
            if(settings('propertybase'))
            {
                // If Propertybase, Load the URL String....
                $path = $this->path;
                if(!empty($path))
                {
                    $path = $this->path;
                }
                else
                {
                    $path = default_thumbnail();
                }
            }
            else
            {
                if(preg_match('/^https?\:\/\//i', $this->path)) // absolute url
                    $path = $this->path;
                else
                    $path = (Storage::exists($this->path)) ? storage_url($this->path) : default_thumbnail();
            }
        }

        return $path;
    }


}
