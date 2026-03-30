<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class whatsapp_clicks extends Model
{

    function totalwhatsappclicks(){
        return whatsapp_clicks::get()->count();
    }

}