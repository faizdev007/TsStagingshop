<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'note_id';

    protected $appends = ['friendly_date', 'shortened_text'];

    public function property()
    {
        return $this->hasOne('App\Property', 'id', 'property_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    function getFriendlyDateAttribute()
    {
        return date("jS F Y", strtotime($this->created_at));
    }

    function getShortenedTextAttribute()
    {
        if (strlen($this->note_content) <= 150)
        {
            return $this->note_content;
        }
        else
        {
            echo substr($this->note_content, 0, 150) . '...';

        }
    }

}
