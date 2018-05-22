<?php

namespace App\Modules\Importrss\Providers;

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
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'importrss');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'importrss');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations', 'importrss');
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
