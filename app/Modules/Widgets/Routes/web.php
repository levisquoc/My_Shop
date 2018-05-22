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

    Route::resource('widgets', 'WidgetController');
    Route::group(['as' => 'widgets.', 'prefix' => 'widgets'], function () {

        //Get categories server side route
        Route::get('server_side/getWidgets', ['as' => 'serverside.getData', 'uses' => 'WidgetController@getData']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'WidgetController@deleteSelected']);

        //Get slug
        Route::get('action/getSlug', ['as' => 'getSlug', 'uses' => 'WidgetController@getSlug']);
    });

});

