<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use App\Notifications\MemberResetPasswordNotification as ResetPasswordNotifier;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'last_login_at', 'last_login_ip'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Accessor:
     * Get the fullname
     *
     */
    public function getFullnameAttribute()
    {
        //return $this->first_name.' '.$this->last_name;
        return $this->name;
    }

    public function scopeGetWhereRole($query, $id)
    {
        return $query->where('role_id', $id)->orderBy('name', 'asc');
    }

    public function scopeGetWhereEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    public function simpleListAgents()
    {
        return $this->getWhereRole(3)->get()->pluck('email', 'id')->toArray();
    }

    public function role()
    {
        return $this->hasOne('App\Models\UserRole', 'user_role_id', 'role_id');
    }

    public function branch()
    {
        return $this->hasOne('App\Models\Branch', 'branch_id', 'branch_id');
    }

    public function property_alerts()
    {
        return $this->hasMany('App\PropertyAlert', 'user_id', 'id');
    }

    public function shortlists()
    {
        return $this->hasMany('App\Shortlist', 'user_id', 'id');
    }

    public function saved_searches()
    {
        return $this->hasMany('App\Models\SaveSearch', 'user_id', 'id');
    }

    /**
     * Accessor:
     * Get the Primary Photo
     *
     */
    public function getPrimaryPhotoAttribute()
    {
        $check_exists = (Storage::exists($this->path)) ? true : false;
        if( !$check_exists ){
            $path = themeAsset('images/logos/logo.png');
        }else{
            $path = (!empty($this->path)) ? $this->path : themeAsset('images/logos/logo.png');
        }
        return $path;
    }

    /**
     * Accessor:
     * Get the Primary Photo
     *
     */
    public function getMainPhotoAttribute()
    {
        //$check_exists = (Storage::exists($this->path)) ? true : false;
        //var_dump($this); exit();
        if( !$this->path ){
            $path = themeAsset('images/logos/logo.png');
        }else{
            $path = (!empty($this->path)) ? storage_url($this->path) : themeAsset('images/logos/logo.png');
        }
        return $path;
    }

    /**
     * Accessor:
     * Get the Status
     *
     */
    public function getStatusLabelAttribute()
    {
        $status='';
        $states = u_states();
        if(!empty($states[$this->status]))
        $status = $states[$this->status];
        return $status;
    }


    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotifier($token));
    }
}
