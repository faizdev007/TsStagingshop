<?php

namespace App;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TranslateTrait;

class Slide extends Model
{
    use TranslateTrait;

    /**
     * Slide table
     */
    protected $table = 'slides';
    protected $primaryKey = 'id';

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function getTextLine1Attribute()
    {
        if(settings('translations'))
        {
            if($this->translations()->count() > 0 && config('app.locale') !== $this->get_default_language())
            {
                $translation = $this->translations()->where('language', config('app.locale'))->pluck('text_line1')->first();
                if($translation)
                {
                    return $translation;
                }
                else
                {
                    // Always Return BAse Language V
                    return $this->attributes['text_line1'];
                }
            }
            else
            {
                return $this->attributes['text_line1'];
            }
        }
        else
        {
            return $this->attributes['text_line1'];
        }
    }

    public function getTextLine2Attribute()
    {
        if(settings('translations'))
        {
            if($this->translations()->count() > 0 && config('app.locale') !== $this->get_default_language())
            {
                $translation = $this->translations()->where('language', config('app.locale'))->pluck('text_line2')->first();

                if($translation)
                {
                    return $translation;
                }
                else
                {
                    // Always Return BAse Language V
                    return $this->attributes['text_line2'];
                }
            }
            else
            {
                return $this->attributes['text_line2'];
            }
        }
        else
        {
            return $this->attributes['text_line2'];
        }
    }
}
