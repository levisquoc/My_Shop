<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Modules\Posts\Models\Category::class, function (Faker\Generator $faker) {
    $name = $faker->name;
    $status = $faker->text;
    return [
        'parent' => 0,
        'name' => $name,
        'slug' => str_slug($name),
        'status' => $status
    ];
});

$factory->define(App\Modules\Posts\Models\Post::class, function (Faker\Generator $faker) {
    $title = $faker->title;
    return [
        'title' => $title,
        'slug' => str_slug($title),
        'image' => '',
        'content' => $faker->paragraph,
        'description' => $faker->text,
        'user_id' => '1',
        'category_id' => '1',
        'status' => 'pending',
        'seo_title' => '',
        'seo_description' => '',
        'seo_keyword' => '',
    ];
});