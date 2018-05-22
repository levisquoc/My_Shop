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

Route::group(['prefix' => 'admin/', 'middleware' => 'admin', 'as' => 'site_setting.'], function () {

    Route::resource('settings', 'SettingController');

    Route::group(['as' => 'settings.', 'prefix' => 'settings'], function () {

        //Update setting
        Route::put('update/{group}', ['uses' => 'SettingController@update', 'as' => 'update']);

        //Delete value
        Route::get('{id}/delete_value', ['uses' => 'SettingController@delete_value', 'as' => 'delete_value']);

        //Moved up
        Route::get('{id}/move_up', ['uses' => 'SettingController@move_up', 'as' => 'move_up']);

        //Moved down
        Route::get('{id}/move_down', ['uses' => 'SettingController@move_down', 'as' => 'move_down']);

        //Get slug
        Route::get('action/getSlug', ['as' => 'getSlug', 'uses' => 'SettingController@getSlug']);
    });
});
