<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SectionContent extends Model
{
    use SoftDeletes;

    protected $table = 'section_content';

    public function section()
    {
        return $this->hasOne('App\Models\PageSection', 'id', 'section_id');
    }

    public function page()
    {
        return $this->belongsTo('App\Page', 'page_id', 'id');
    }
}
