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
     * UserActivity routes *
     ********************/
    Route::resource('logs', 'UserActivityController');
    Route::group(['as' => 'logs.', 'prefix' => 'logs'], function () {

        //Get logs server side route all activity
        Route::get('server_side/getlogs', ['as' => 'serverside.getlogs', 'uses' => 'UserActivityController@getdata']);

        //Get logs server side route for detail user
        Route::get('server_side/getlogsuser',
            ['as' => 'serverside.getlogsuser', 'uses' => 'UserActivityController@getdataUser']);

        //Deleted selected item
        Route::get('action/detele_selected',
            ['as' => 'deleteSelected', 'uses' => 'UserActivityController@deleteSelected']);
    });

});