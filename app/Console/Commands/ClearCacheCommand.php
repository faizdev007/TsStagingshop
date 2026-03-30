<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ClearCacheCommand extends Command
{
    protected $signature = 'custom:clear-cache';
    protected $description = 'Clear all caches (application, config, route, and view)';

    public function handle()
    {
        $this->info('Clearing application cache...');
        Artisan::call('cache:clear');
        
        $this->info('Clearing config cache...');
        Artisan::call('config:clear');
        
        $this->info('Clearing route cache...');
        Artisan::call('route:clear');
        
        $this->info('Clearing view cache...');
        Artisan::call('view:clear');
        
        $this->info('All caches have been cleared!');
    }
}