<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FooterBlock extends Model
{
    protected $primaryKey = 'footer_block_id';
    protected $table = 'footer_blocks';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder)
        {
            $builder->orderBy('footer_block_order', 'asc');
        });
    }

    public function links()
    {
        return $this->hasMany('App\FooterLink', 'footer_block_id', 'footer_block_id');
    }
}
