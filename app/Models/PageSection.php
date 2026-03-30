<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class PageSection extends Model
{
    use SoftDeletes;

    protected $table = 'page_sections';

    public function page()
    {
        return $this->hasOne('App\Page', 'id', 'page_id');
    }

    public function section_contents()
    {
        return $this->hasMany('App\Models\SectionContent', 'section_id', 'id')->orderBy('order', 'asc');
    }

    public function getUrlAttribute()
    {
        $url = $this->title;

        $url = str_replace(' ', '_', $url);

        return strtolower($url);
    }
}
