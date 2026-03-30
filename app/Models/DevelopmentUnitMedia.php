<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevelopmentUnitMedia extends Model
{
    protected $table = 'development_unit_media';

    public function unit()
    {
        return $this->hasOne('App\Models\DevelopmentUnit', 'unit_id', 'id');
    }

    public function development()
    {
        return $this->hasOne('App\Models\Development', 'development_id', 'id');
    }
}
