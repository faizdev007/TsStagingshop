<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FooterLink extends Model
{
    protected $primaryKey = 'footer_link_id';
    protected $table = 'footer_links';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder)
        {
            $builder->orderBy('footer_link_order', 'asc');
        });
    }

    public function footer_block()
    {
        return $this->hasOne('App\FooterBlock', 'footer_block_id', 'footer_block_id');
    }
}
