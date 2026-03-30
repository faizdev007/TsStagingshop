<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostTag extends Model
{
    protected $primaryKey = 'post_tag_id';

    public function post()
    {
        return $this->hasOne('App\Post', 'id', 'post_id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\PostCategory', 'id', 'post_tag_value');
    }

    public function categoryActive()
    {
        return $this->hasOne('App\Models\PostCategory', 'id', 'post_tag_value')->where('is_publish', 1);
    }
}
