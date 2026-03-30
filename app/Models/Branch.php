<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $table = 'branches';
    protected $primaryKey = 'branch_id';

    protected $guarded = [
        'branch_id',
        'action'
    ];

    public function properties()
    {
        return $this->hasMany('App\Property', 'branch_id', 'branch_id');
    }

    public function users()
    {
        return $this->hasMany('App\User', 'branch_id', 'branch_id');
    }

    public function leads()
    {
        return $this->hasMany('App\Enquiry', 'branch_id', 'branch_id');
    }
}
