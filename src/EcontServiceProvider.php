<?php

namespace Gdinko\Econt;

use Illuminate\Support\ServiceProvider;

class EcontServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/econt.php' => config_path('econt.php'),
            ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/econt.php', 'econt');

        // Register the main class to use with the facade
        $this->app->singleton('econt', function () {
            return new Econt();
        });
    }
}
