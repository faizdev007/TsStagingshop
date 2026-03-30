<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $appends = ['friendly_date', 'friendly_time', 'friendly_read_date'];
    protected $primaryKey = 'message_id';

    public function sender()
    {
        return $this->hasOne('App\User', 'id', 'from_id');
    }

    public function receiver()
    {
        return $this->hasOne('App\User', 'id', 'to_id');
    }

    public function getFriendlyReadDateAttribute()
    {
        return date("jS F Y", strtotime($this->message_read_date));
    }

    public function getFriendlyReadTimeAttribute()
    {
        return date("H:ia", strtotime($this->message_read_date));
    }

    function getFriendlyDateAttribute()
    {
        return date("jS F Y", strtotime($this->created_at));
    }

    function getFriendlyTimeAttribute()
    {
        return date("H:ia", strtotime($this->created_at));
    }
}
