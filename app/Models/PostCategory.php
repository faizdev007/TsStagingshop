<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{

    public function tags()
    {
        return $this->hasMany('App\PostTag', 'post_tag_value', 'id');
    }

    public function getIsPublishDisplayAttribute()
    {
        $data = ($this->is_publish == 1) ? 'Yes' : 'No';
        return $data;
    }

    public static function findByName($name)
    {
        return $query = PostCategory::where('name', '=', urldecode($name))->first();
    }

}
