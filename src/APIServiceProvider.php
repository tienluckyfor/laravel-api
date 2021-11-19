<?php

namespace Codeby\LaravelApi;

use Codeby\LaravelApi\commands\PublishAPI;
use Illuminate\Support\ServiceProvider;

class APIServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'laravel-api');
        require_once __DIR__.'/_class/config.php';
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('laravel-api:publish', function ($app) {
            return new PublishAPI();
        });

        $this->commands([
            'laravel-api:publish',
        ]);
    }
}
