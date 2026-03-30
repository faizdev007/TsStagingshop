<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailValuationMessage extends Model
{
    protected $table = 'valuation_messages';

    public function email()
    {
        return $this->hasOne('App\Models\SentEmail', 'id', 'message_id');
    }
}
