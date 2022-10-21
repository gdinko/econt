<?php

namespace Gdinko\Econt;

use Gdinko\Econt\Commands\GetCarrierEcontApiStatus;
use Gdinko\Econt\Commands\GetCarrierEcontPayments;
use Gdinko\Econt\Commands\MapCarrierEcontCities;
use Gdinko\Econt\Commands\SyncCarrierEcontAll;
use Gdinko\Econt\Commands\SyncCarrierEcontCities;
use Gdinko\Econt\Commands\SyncCarrierEcontCountries;
use Gdinko\Econt\Commands\SyncCarrierEcontOffices;
use Gdinko\Econt\Commands\SyncCarrierEcontQuarters;
use Gdinko\Econt\Commands\SyncCarrierEcontStreets;
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
            ], 'econt-config');

            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'econt-migrations');

            $this->publishes([
                __DIR__ . '/Models/' => app_path('Models'),
            ], 'econt-models');

            $this->publishes([
                __DIR__ . '/Commands/' => app_path('Console/Commands'),
            ], 'econt-commands');

            // Registering package commands.
            $this->commands([
                SyncCarrierEcontAll::class,
                SyncCarrierEcontCountries::class,
                SyncCarrierEcontCities::class,
                SyncCarrierEcontOffices::class,
                SyncCarrierEcontStreets::class,
                SyncCarrierEcontQuarters::class,
                GetCarrierEcontPayments::class,
                GetCarrierEcontApiStatus::class,
                MapCarrierEcontCities::class,
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
