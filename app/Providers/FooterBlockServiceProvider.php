<?php

namespace App\Providers;

use App\FooterBlock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class FooterBlockServiceProvider extends ServiceProvider
{
    public function boot(Request $request)
    {
        // Fix For Migration Error on Deploy...
        if (Schema::hasTable('footer_blocks'))
        {
            $footer_blocks = FooterBlock::all();
        }
        else
        {
            // Return Blank Collection...
            $footer_blocks = collect([]);
        }

        view()->share('footer_blocks', $footer_blocks);
    }
}
