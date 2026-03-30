<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TranslationController extends Controller
{
    public $is_set;
    public function __construct()
    {
        $this->is_set = settings('translations');

        //Prevent Access if Turned Of....
        if($this->is_set == '0' || !$this->is_set)
        {
            return false;
        }
    }

    public function translate($to_language)
    {

    }
}
