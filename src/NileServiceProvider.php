<?php

namespace Nile\LaravelServer;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class NileServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nile.php', 'nile');

        $this->app->singleton(NileServerSDK::class, function () {
            return new NileServerSDK();
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/nile.php' => config_path('nile.php'),
        ], 'config');

        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }
}
