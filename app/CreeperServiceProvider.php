<?php

namespace App;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use App\Facade\Creeper as CreeperFacade;

class CreeperServiceProvider extends ServiceProvider
{
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $loader->alias('Creeper', CreeperFacade::class);

        $this->app->bind('creeper', 'App\Creeper');
    }
}