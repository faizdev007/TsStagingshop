<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    protected $primaryKey = 'team_member_id';
    protected $table = 'team_members';

    protected $casts = [
        'team_member_experties' => 'array',
        'team_member_setting' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
