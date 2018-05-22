<?php

namespace App\Modules\Contacts\Providers;

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
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/Lang', 'contacts');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'contacts');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations', 'contacts');
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
