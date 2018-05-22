<?php

namespace App\Modules\Userlogs\Providers;

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
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'userlogs');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'userlogs');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations', 'userlogs');
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
