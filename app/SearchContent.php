<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SearchContent extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'search_content';

    public function blocks()
    {
        return $this->hasMany('App\SearchContentBlock', 'search_content_id', 'id');
    }
}
