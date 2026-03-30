<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class TemplateServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $template = themeOptions();

        // Safely access request (works for web, CLI, queues)
        $search_template = request()->get('template');

        view()->share([
            'demo_number'     => $template,
            'search_template' => $search_template,
            'isProduction'    => ! App::environment(['local', 'staging']),
        ]);
    }
}