<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Property;
use Illuminate\Support\Str;

class Community extends Model
{
    protected $table = 'communities';

    // public function getProperties(){
    //     return $this->belongsToMany(Property::class);
    // }

    protected $casts = [
        'photos' => 'array',
    ];

    public function getPhotoDisplayAttribute()
    {
        $path = isset($this->photo) && (Storage::exists($this->photo)) ? storage_url($this->photo) : default_thumbnail();
        return $path;
    }

    public function getIsPublishDisplayAttribute()
    {
        $data = ($this->is_publish == 1) ? 'Yes' : 'No';
        return $data;
    }

    public function getTruncateContentAttribute()
    {
        return Str::words(strip_tags($this->content), 70, '...');
        // return implode('', array_slice($matches[0], 0, 2)); // first 2 <p>
    }

    public function getUrlAttribute()
    {
        $url = $this->name;
        $url = 'property-for-sale/community/'.urlencode($url);
        return strtolower($url);
    }

    public static function findByName($name)
    {
        return $query = Community::where('name', '=', urldecode($name))->first();
    }

    // query scope to filter down to Published posts
    public function scopePublished($query)
    {
        return $query->where('is_publish', 1);
    }

}
