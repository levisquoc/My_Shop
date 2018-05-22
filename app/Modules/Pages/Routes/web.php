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

Route::group(['prefix' => 'admin/', 'middleware' => 'admin'], function () {

    Route::resource('pages', 'PageController');
    Route::group(['as' => 'pages.', 'prefix' => 'pages'], function () {

        //Get categories server side route
        Route::get('server_side/getPages', ['as' => 'serverside.getData', 'uses' => 'PageController@getData']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'PageController@deleteSelected']);

        //Get slug
        Route::get('action/getSlug', ['as' => 'getSlug', 'uses' => 'PageController@getSlug']);
    });

});
