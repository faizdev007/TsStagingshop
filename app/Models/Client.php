<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $primaryKey = 'client_id';

    public function valuations()
    {
        return $this->hasMany('App\Models\ClientValuation', 'client_id', 'client_id');
    }
}
