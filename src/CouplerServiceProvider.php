<?php

namespace BereanK\Coupler;


use Illuminate\Support\ServiceProvider;

class CouplerServiceProvider extends ServiceProvider
{
    public function register()
    {

        $this->mergeConfigFrom(__DIR__ . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php", 'coupler');
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . DIRECTORY_SEPARATOR . "config" . DIRECTORY_SEPARATOR . "config.php" => config_path('coupler.php'),
            ], 'config');
        }

      
    }
}
