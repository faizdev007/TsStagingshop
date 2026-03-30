<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ZooplaEtag extends Model
{
    protected $table = 'zoopla_etags';

    protected $fillable = ['property_id'];

    public function property()
    {
        return $this->hasOne('App\Property', 'property_id', 'property_id');
    }
}
