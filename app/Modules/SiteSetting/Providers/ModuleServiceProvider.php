<?php

namespace App\Modules\SiteSetting\Providers;

use Caffeinated\Modules\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the module services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'site-setting');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'site-setting');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations', 'site-setting');
    }

    /**
     * Register the module services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
    }
}
