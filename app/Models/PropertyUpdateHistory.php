<?php

namespace App\Models;

use App\Property;
use Illuminate\Database\Eloquent\Model;

class PropertyUpdateHistory extends Model
{
    protected $table = 'property_update_histories';

    protected $fillable = [
        'property_id',
        'ref',
        'userName',
        'changeLog',
        'document_name',
        'document_type',
    ];

    public $timestamps = true;

    public function property()
    {
        return $this->belongsTo(
            Property::class,
            'property_id'
        );
    }
}
