<?php

namespace Gdinko\Econt;

use Gdinko\Econt\Commands\SyncCarrierEcontAll;
use Gdinko\Econt\Commands\SyncCarrierEcontCities;
use Gdinko\Econt\Commands\SyncCarrierEcontCountries;
use Gdinko\Econt\Commands\SyncCarrierEcontOffices;
use Illuminate\Support\ServiceProvider;

class EcontServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/econt.php' => config_path('econt.php'),
            ], 'config');

            // Registering package commands.
            $this->commands([
                SyncCarrierEcontAll::class,
                SyncCarrierEcontCountries::class,
                SyncCarrierEcontCities::class,
                SyncCarrierEcontOffices::class,
            ]);
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
