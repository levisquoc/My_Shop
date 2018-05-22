<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your module. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::group(['prefix' => 'admin/', 'middleware' => 'admin', 'as' => 'blog.'], function () {

    /********************
     * Categories routes *
     ********************/
    Route::resource('categories', 'CategoryController');
    Route::group(['as' => 'categories.', 'prefix' => 'categories'], function () {

        //Get categories server side route
        Route::get('server_side/getCategories',
            ['as' => 'serverside.getcategories', 'uses' => 'CategoryController@getdata']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'CategoryController@deleteSelected']);

        //Get slug
        Route::get('action/getSlug', ['as' => 'getSlug', 'uses' => 'CategoryController@getSlug']);

        //call when restore trash
        Route::get('action/restore/{id}', ['as' => 'restore', 'uses' => 'CategoryController@restore']);

        //call when remove item form trash table
        Route::get('action/remove/{id}', ['as' => 'remove', 'uses' => 'CategoryController@remove']);

        //call when remove select item form trash table
        Route::get('action/detele_trash_selected',
            ['as' => 'deleteTrashSelected', 'uses' => 'CategoryController@deleteTrashSelected']);

        //call when remove select item form trash table
        Route::get('action/restore_trash_selected',
            ['as' => 'restoreTrashSelected', 'uses' => 'CategoryController@restoreTrashSelected']);
    });

    /***************
     * Posts routes *
     ***************/
    Route::resource('posts', 'PostController');
    Route::group(['as' => 'posts.', 'prefix' => 'posts'], function () {

        //Get posts server side route
        Route::get('server_side/getPosts', ['as' => 'serverside.getposts', 'uses' => 'PostController@getdata']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'PostController@deleteSelected']);

        //Get slug
        Route::get('action/getSlug', ['as' => 'getSlug', 'uses' => 'PostController@getSlug']);

        //call when restore trash
        Route::get('action/restore/{id}', ['as' => 'restore', 'uses' => 'PostController@restore']);

        //call when remove item form trash table
        Route::get('action/remove/{id}', ['as' => 'remove', 'uses' => 'PostController@remove']);

        //call when remove select item form trash table
        Route::get('action/detele_trash_selected',
            ['as' => 'deleteTrashSelected', 'uses' => 'PostController@deleteTrashSelected']);

        //call when remove select item form trash table
        Route::get('action/restore_trash_selected',
            ['as' => 'restoreTrashSelected', 'uses' => 'PostController@restoreTrashSelected']);
    });

    /***************
     * Tags routes *
     ***************/
    Route::resource('tags', 'TagController');
    Route::group(['as' => 'tags.', 'prefix' => 'tags'], function () {

        //Get posts server side route
        Route::get('server_side/getTags', ['as' => 'serverside.gettags', 'uses' => 'TagController@getdata']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'TagController@deleteSelected']);

        //Get slug
        Route::get('action/getSlug', ['as' => 'getSlug', 'uses' => 'TagController@getSlug']);

        //call when restore trash
        Route::get('action/restore/{id}', ['as' => 'restore', 'uses' => 'TagController@restore']);

        //call when remove item form trash table
        Route::get('action/remove/{id}', ['as' => 'remove', 'uses' => 'TagController@remove']);

        //call when remove select item form trash table
        Route::get('action/detele_trash_selected',
            ['as' => 'deleteTrashSelected', 'uses' => 'TagController@deleteTrashSelected']);

        //call when remove select item form trash table
        Route::get('action/restore_trash_selected',
            ['as' => 'restoreTrashSelected', 'uses' => 'TagController@restoreTrashSelected']);
    });

});
