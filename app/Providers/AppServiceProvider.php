<?php

namespace App\Providers;

use App\Mail\PropertyUpdated;
use App\Models\Languages;
use App\Models\LeadAutomation;
use App\Models\Message;
use App\Observers\PropertyObserver;
use App\Observers\SubscriberObserver;
use App\Property;
use App\Shortlist;
use App\Subscriber;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Watch Property Model For Updates...
        Property::observe(PropertyObserver::class);
        Subscriber::observe(SubscriberObserver::class);
        
        // Set Translate Options...
        if (Schema::hasTable('languages')) {
            view()->share('translate_settings', Languages::first());
        }

        Schema::defaultStringLength(191);

        Blade::directive('datetime', function ($expression) {
            return "<?php echo ($expression)->format('m/d/Y H:i'); ?>";
        });
        // Currencies
        $all_currencies = all_currencies();
        view()->share('all_currencies', $all_currencies);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
