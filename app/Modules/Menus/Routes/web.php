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

Route::group(['prefix' => 'admin/', 'middleware' => 'admin', 'as' => 'menus.'], function () {
    Route::get('menus', ['as' => 'index', 'uses' => 'MenuController@index']);
    Route::post('menus/select/module', ['as' => 'select.module', 'uses' => 'MenuController@selectModule']);
});
