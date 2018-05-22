<?php

namespace App\Modules\Analytics\Providers;

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
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'analytics');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'analytics');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations', 'analytics');
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
