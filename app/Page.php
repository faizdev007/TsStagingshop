<?php

namespace App;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\TranslateTrait;

class Page extends Model
{
    use TranslateTrait;
    use Sluggable;
    use SoftDeletes;

    public function sluggable(): array
    {
        return [
            'route' => [
                'source' => 'title'
            ]
        ];
    }

    public function sections()
    {
        return $this->hasMany('App\Models\PageSection', 'page_id', 'id')->orderBy('order', 'asc');
    }


    // Featured banner
    public function getBannerImageAttribute()
    {
        //test...
        $banner = !empty($this->photo) ? asset('storage/'.$this->photo) : false;
        $banner_style = !empty($banner) ? 'style="background-image:url('.$banner.')"' : false;
        return $banner_style;
    }

    // Featured banner
    public function getBannerDataBGAttribute()
    {
        //test...
        $banner = !empty($this->photo) ? asset('storage/'.$this->photo) : themeAsset('images/page-title/banner-placeholder.jpg');
        $banner_style = !empty($banner) ? 'data-background="url('.$banner.')"' : false;
        return $banner_style;
    }

    public function getDisplayPhotoHomeAttribute()
    {
        $banner = !empty($this->photo) ? asset('storage/'.$this->photo) : themeAsset('images/home/content-image.jpg');
        return $banner;
    }

    public function getDisplayPhotoAttribute()
    {
        $banner = !empty($this->photo) ? asset('storage/'.$this->photo) : '';
        return $banner;
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function getTitleAttribute()
    {
        $field_name = 'title';
       if(settings('translations') && config('app.locale') !== $this->get_default_language())
        {

            $field_lang = $this->translations()->where('language', config('app.locale'))->pluck($field_name)->first();

            if( $this->translations()->count() > 0 &&
                config('app.locale') !== $this->get_default_language() &&
                !empty($field_lang)
                )
            {
                return $field_lang;
            }
            else
            {
                return $this->attributes[$field_name];
            }
        }
        else
        {
            return $this->attributes[$field_name];
        }
    }

    public function getContentAttribute()
    {
        $field_name = 'content';
       if(settings('translations') && config('app.locale') !== $this->get_default_language())
        {

            $field_lang = $this->translations()->where('language', config('app.locale'))->pluck($field_name)->first();

            if( $this->translations()->count() > 0 &&
                config('app.locale') !== $this->get_default_language() &&
                !empty($field_lang)
                )
            {
                return $field_lang;
            }
            else
            {
                return $this->attributes[$field_name];
            }
        }
        else
        {
            return $this->attributes[$field_name];
        }
    }

    public function getMetaAttribute()
    {
        $field_name = 'meta';
       if(settings('translations') && config('app.locale') !== $this->get_default_language())
        {
            $field_lang = $this->translations()->where('language', config('app.locale'))->pluck($field_name)->first();

            if( $this->translations()->count() > 0 &&
                config('app.locale') !== $this->get_default_language() &&
                !empty($field_lang)
                )
            {
                return $field_lang;
            }
            else
            {
                return $this->attributes[$field_name];
            }
        }
        else
        {
            return $this->attributes[$field_name];
        }
    }

}
