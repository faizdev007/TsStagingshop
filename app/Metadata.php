<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Metadata extends Model
{
    /**
     * Slide table
     */
    protected $table = 'metadata';
    protected $primaryKey = 'id';

    /**
     * Accessor:
     * Display excerpt
     *
     */
    public function getExcerptAttribute()
    {
        $value = Str::limit(strip_tags($this->description), 50, '...');
        return $value;
    }

    /**
     * Get metadata with URL
     *
     */
    public static function getMetadata($url)
    {
        $result = Metadata::where('url', $url)->first();
        return $result;
    }
}
