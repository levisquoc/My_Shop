<?php

namespace App\Modules\Widgets\Providers;

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
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'widgets');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'widgets');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations', 'widgets');
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
