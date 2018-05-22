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

    Route::resource('contacts', 'ContactController');
    Route::group(['as' => 'contacts.', 'prefix' => 'contacts'], function () {

        //Get categories server side route
        Route::get('server_side/getcontacts', ['as' => 'serverside.getData', 'uses' => 'ContactController@getData']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'ContactController@deleteSelected']);
    });
});
