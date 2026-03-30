<?php

namespace App;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use App\Traits\TranslateTrait;

class Testimonial extends Model
{
    use TranslateTrait;

    /**
     * Slide table
     */
    protected $table = 'testimonials';
    protected $primaryKey = 'id';

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function getQuoteAttribute()
    {
        $field = 'quote';

        if (!settings('translations')) {
            return $this->attributes[$field];
        }

        $translation = $this->translations
            ->where('language', config('app.locale'))
            ->first();

        if (
            $translation &&
            config('app.locale') !== $this->get_default_language() &&
            !empty($translation->$field)
        ) {
            return $translation->$field;
        }

        return $this->attributes[$field];
    }

}
