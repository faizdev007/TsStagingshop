<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();
        //$this->mapWebRoutes();
        $this->mapTranslationRoutes();
        $this->mapFrontendRoutes();
        $this->mapBackendRoutes();
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
     /*
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }
    */


    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }


    /**
    * Define the "front" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapFrontendRoutes()
    {
       Route::middleware('web')
            ->namespace($this->namespace.'\Frontend')
            ->group(base_path('routes/frontend.php'));
    }

    /**
    * Define the "manage" routes for the application.
    *
    * These routes all receive session state, CSRF protection, etc.
    *
    * @return void
    */
    protected function mapBackendRoutes()
    {
       Route::middleware('web') // maybe a extra middleware is needed to check the users role/permission etc.
            ->namespace($this->namespace.'\Backend')
            ->group(base_path('routes/backend.php'));
    }

    protected function mapTranslationRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace.'\Frontend')
            ->group(base_path('routes/translations.php'));
    }

}
