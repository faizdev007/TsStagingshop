<?php

namespace App;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use App\Traits\TranslateTrait;
use App\PostTag;
use Illuminate\Support\Str;

class Post extends Model
{
    use TranslateTrait;
    use Sluggable;

    public $primaryKey = 'id';

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    // query scope to filter down to Published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // query scope to order by the latest posts
    // calling it scopeLatest() made it use the default 'created_at', so here's our custom one
    public function scopeNewest($query)
    {
        return $query->orderByDesc('date_published');
    }

    /**
     * Accessor:
     * get an extract of the post content for most News/
     * Latest News templates - it's about 210 characters
     */
    public function getExcerptAttribute()
    {
        $value = $this->content;
        $value = strip_tags($value);
        $value = Str::limit($value, 210);

        return $value;
    }

    /**
     * Accessor:
     * get a (shorter) extract of the content for the second
     * Latest News (widget) template - about 110 characters
     */
    public function getShortExcerptAttribute()
    {
        $value = $this->content;
        $value = strip_tags($value);
        $value = Str::limit($value, 110);

        return $value;
    }

    public function getshortExcerptLongAttribute()
    {
        $value = $this->content;
        $value = strip_tags($value);
        $value = Str::limit($value, 310);

        return $value;
    }


    /**
     * Accessor:
     * the date to be displayed on the News and Article pages
     */
    public function getFullDateAttribute()
    {
        return ($this->date_published != null) ? date('jS F Y', strtotime($this->date_published)) : $this->created_at->format('jS F Y');
    }

    /**
     * Accessor:
     * the date to be displayed on Latest News sections
     */
    public function getSnippetDateAttribute()
    {
        return ($this->date_published != null) ? date('d/m/Y', strtotime($this->date_published)) : $this->created_at->format('d/m/Y');
    }

    public function getFriendlyDateAttribute()
    {
        return ($this->date_published != null) ? date('jS F Y', strtotime($this->date_published)) : $this->created_at->format('d/m/Y');
    }

    /**
     * Accessor:
     * the date to be displayed on Latest News sections
     */
    public function getSnippetDateDemo2Attribute()
    {
        return ($this->date_published != null) ? date('jS F', strtotime($this->date_published)) : $this->created_at->format('d/m/Y');
    }

    /**
     * Accessor:
     * the full URL of the article
     */
    public function getUrlAttribute()
    {
        $value = sprintf('%s/%s',
            'article', // Article route
            $this->slug // post identifier
        );

        return lang_url($value);
    }


    /**
     * Accessor:
     * Get the Primary Photo
     *
     */
    public function getPrimaryPhotoAttribute()
    {
        $thumb = !empty($this->photo) ? asset('storage/posts/'.basename($this->photo)) : default_thumbnail();
        return $thumb;
    }

    public function getPrimaryPhotoFlagAttribute()
    {
        $flag = !empty($this->photo) ? true : false;
        return $flag;
    }

    public function postTags()
    {
        return $this->belongsTo('App\PostTag', 'id', 'post_id');
    }

    public function tags()
    {
        return $this->hasMany('App\PostTag', 'post_id', 'id');
    }

    public function location_tags()
    {
        return $this->tags()->where('type', 'location')->orderBy('created_at', 'DESC');
    }

    public function category_tags()
    {
        return $this->tags()->where('type', 'post_categories')->orderBy('created_at', 'DESC');
    }

    public function getCategoryTagsArrayAttribute()
    {
        $array = [];
        if(!$this->category_tags->isEmpty()){
            foreach($this->category_tags as $tag):
                if(!empty( $tag->categoryActive )):
                    $array[url('blog?category='.$tag->categoryActive->id)] = $tag->categoryActive->name;
                endif;
            endforeach;
        }
        return $array;
    }

    public function getTitleAttribute()
    {
        if(settings('translations'))
        {
            if($this->translations()->count() > 0 && config('app.locale') !== $this->get_default_language())
            {
                $translation = $this->translations()->where('language', config('app.locale'))->pluck('title')->first();

                if($translation)
                {
                    return $translation;
                }
                else
                {
                    // Always Return BAse Language V
                    return $this->attributes['title'];
                }
            }
            else
            {
                return $this->attributes['title'];
            }
        }
        else
        {
            return $this->attributes['title'];
        }
    }

    public function getContentAttribute()
    {
        if(settings('translations'))
        {
            if($this->translations()->count() > 0 && config('app.locale') !== $this->get_default_language())
            {
                $translation = $this->translations()->where('language', config('app.locale'))->pluck('content')->first();
                if($translation)
                {
                    return $translation;
                }
                else
                {
                    // Always Return BAse Language V
                    return $this->attributes['content'];
                }

            }
            else
            {
                return $this->attributes['content'];
            }
        }
        else
        {
            return $this->attributes['content'];
        }
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translationable');
    }

    public function getMetaAttribute()
    {
        $field_name = 'meta';
        if(settings('translations'))
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
