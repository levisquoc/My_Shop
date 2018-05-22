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

    Route::resource('ads', 'AdsController');
    Route::group(['as' => 'ads.', 'prefix' => 'ads'], function () {

        //Get ads server side route
        Route::get('server_side/getAds', ['as' => 'serverside.getads', 'uses' => 'AdsController@getdata']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'AdsController@deleteSelected']);

    });

});
