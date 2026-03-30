<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $table = 'users';
    protected $fillable = ['last_login_at', 'last_login_ip'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function role()
    {
        return $this->belongsTo('App\Models\UserRole', 'role_id', 'user_role_id');
    }
}
