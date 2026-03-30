<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientValuation extends Model
{
    use GeneratesUuid;
    use SoftDeletes;

    protected $table = 'client_valuations';
    protected $primaryKey = 'client_valuation_id';

    public function client()
    {
        return $this->hasOne('App\Models\Client', 'client_id', 'client_id');
    }

    public function property_type()
    {
        return $this->hasOne('App\PropertyType', 'id', 'property_type_id');
    }

    public function emails()
    {
        return $this->hasMany('App\Models\EmailValuationMessage', 'client_valuation_id', 'client_valuation_id')->orderBy('created_at', 'DESC');
    }

    public function notes()
    {
        return $this->hasMany('App\Models\ClientValuationNote', 'client_valuation_id', 'client_valuation_id')->orderBy('created_at', 'DESC');
    }

    public function property()
    {
        return $this->hasOne('App\Property', 'client_valuation_id', 'client_valuation_id');
    }

    function getFriendlyDateAttribute()
    {
        return date("jS F Y", strtotime($this->created_at));
    }
}
