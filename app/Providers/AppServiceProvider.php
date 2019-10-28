<?php

namespace App\Providers;

use App\Services\TranskripService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Services\TranskripService', function ($app) {
            $transkrip = new TranskripService();
            $transkrip->setApp($app);
            return $transkrip;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
