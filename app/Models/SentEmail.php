<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    protected $table = 'sent_emails';

    protected $appends = ['friendly_date'];

    function getFriendlyDateAttribute()
    {
        return date("jS F Y", strtotime($this->created_at));
    }
}
