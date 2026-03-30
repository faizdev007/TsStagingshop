<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyCategory extends Model
{
    public function property()
    {
        return $this->hasOne('App\Property', 'property_id', 'id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'category_id', 'id');
    }
}
