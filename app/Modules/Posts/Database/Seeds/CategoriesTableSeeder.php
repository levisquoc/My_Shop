<?php

use Illuminate\Database\Seeder;
use App\Category;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'parent' => 0
            'name' => 'Uncategorized',
            'slug' => 'uncategorized',
            'status' => 'pending'
        ]);
        factory(Category::class, 50)->create();
    }
}