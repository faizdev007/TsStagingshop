<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialMedia extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'social_media_id';

    public function branch()
    {
        return $this->hasOne('App\Models\Branch', 'branch_id', 'branch_id');
    }
}
