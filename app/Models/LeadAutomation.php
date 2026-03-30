<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LeadAutomation extends Model
{
    protected $table = 'lead_automations';
    protected $primaryKey = 'lead_automation_id';

    public function lead()
    {
        return $this->hasOne('App\Enquiry', 'id', 'lead_id');
    }

    public function messages()
    {
        return $this->hasMany('App\Models\LeadAutomationMessage', 'automation_id', 'lead_automation_id')->orderBy('created_at', 'DESC');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function getLastUpdatedAttribute()
    {
        return date("jS F Y", strtotime($this->updated_at));
    }

    public function getNextMessageAttribute()
    {
        $dt = Carbon::parse($this->last_contacted);
        $dt->addWeek(1);

        // Could Be $dt->addDays(n);

        return date("jS F Y", strtotime($dt));
    }
}
