<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadAutomationMessage extends Model
{
    protected $table = 'lead_automation_messages';
    protected $primaryKey = 'lead_automation_message_id';

    public function lead()
    {
        return $this->hasOne('App\Enquiry', 'id', 'lead_id');
    }

    public function automation()
    {
        return $this->hasOne('App\Models\LeadAutomation', 'automation_id', 'lead_automation_id');
    }

    public function email()
    {
        return $this->hasOne('App\Models\SentEmail', 'id', 'message_id');
    }
}
