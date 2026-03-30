<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Translation extends Model
{
    protected $table = 'translations';
    protected $primaryKey = 'id';

    protected $fillable = ['title', 'text_line1', 'text_line2', 'description', 'quote', 'language', 'rating', 'content', 'translationable_id', 'translationable_type', 'meta'];

    use SoftDeletes;

    public function translationable()
    {
        return $this->morphTo();
    }
}
