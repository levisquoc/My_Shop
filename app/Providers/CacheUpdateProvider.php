<?php

namespace App\Providers;

use App\Modules\Posts\Models\Category;
use App\Modules\Posts\Models\Post;
use App\Modules\SiteSetting\Models\Setting;
use Harimayco\Menu\Models\MenuItems;
use Harimayco\Menu\Models\Menus;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheUpdateProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Setting::updated(function ($setting) {
            Cache::forget('setting-' . $setting->key);
        });

        Menus::updated(function ($menu) {
            Cache::forget('menu-' . $menu->name);
        });

        MenuItems::updated(function ($menu_item) {
            $menu = Menus::find($menu_item->menu);
            Cache::forget('menu-item-' . $menu->name);
        });

        MenuItems::saved(function ($menu_item) {
            $menu = Menus::find($menu_item->menu);
            Cache::forget('menu-item-' . $menu->name);
        });

        Post::updated(function ($post) {
            Cache::flush();
        });

        Post::saved(function ($post) {
            Cache::flush();
        });

        Category::updated(function ($cate) {
            Cache::flush();
        });

        Category::saved(function ($cate) {
            Cache::flush();
        });


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
