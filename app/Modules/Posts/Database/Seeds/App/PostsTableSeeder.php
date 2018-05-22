<?php

namespace App\Modules\Posts\Database\Seeds;

use Illuminate\Database\Seeder;

class App\PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public
    function run()
    {
        factory(App\Modules\Posts\Models\Post::class, 50)->create()->each(function ($u) {
            $u->posts()->save(factory(App\Modules\Posts\Models\Post::class)->make());
        });
    }
}
