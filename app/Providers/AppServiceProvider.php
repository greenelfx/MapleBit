<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Yaml;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // load config/maplebit.yml into Laravel config
        Yaml::loadToConfig(config_path('maplebit.yml'), 'maplebit');
    }
}
