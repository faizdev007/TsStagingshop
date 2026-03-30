<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\DB;

class PropertyType extends Model
{
    use Sluggable;

    public $primaryKey = 'id';

    /**
     * We are not using an "updated_at" field
     *
     */
    public $timestamps = false;

    public function scopePropertyType($query)
    {
        return $query->where('types', 'property_type')->whereNotIn('slug',['land','marina','any']);
    }
    
    public function properties()
    {
        return $this->hasMany('App\Property', 'property_type_id', 'id');
    }

    public function property()
    {
        return $this->hasOne('App\Property', 'property_type_id', 'id');
    }

    public function scopeCategory($query)
    {
        return $query->where('types', 'category');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public static function findBySlug($slug)
    {
        return $query = PropertyType::where('slug', '=', $slug)->first();
    }
}