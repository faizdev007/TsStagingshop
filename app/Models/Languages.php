<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Languages extends Model
{
    protected $table = 'languages';
    protected $appends = ['languages_array', 'languages_friendly_array'];

    function getLanguagesArrayAttribute()
    {
        $chosen_languages = $this->language_settings;

        return explode(',', $chosen_languages);
    }

    function getLanguagesFriendlyArrayAttribute()
    {
        $chosen_languages = explode(',', $this->language_settings);

        // Get Site Languages...
        $site_languages = config('custom.languages');

        $languages = array();

        foreach($chosen_languages as $chosen_language)
        {
            foreach($site_languages as $k => $v)
            {
                if($k == $chosen_language)
                {
                    $languages[$v] = $k;
                }
            }
        }

        return $languages;
    }
}
