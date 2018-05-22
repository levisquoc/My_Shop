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

Route::group(['prefix' => 'admin/', 'middleware' => 'admin', 'as' => 'rss.'], function () {

    /*****************
     * Rss routes *
     *****************/
    Route::resource('rss', 'RssController');
    Route::group(['as' => 'rss.', 'prefix' => 'rss'], function () {

        //Get categories server side route
        Route::get('server_side/getRSS', ['as' => 'serverside.getrss', 'uses' => 'RssController@getdata']);

        //Get slug
        Route::get('action/getSlug', ['as' => 'getSlug', 'uses' => 'RssController@getSlug']);

        //Download 
        Route::post('rss/download', ['as' => 'download', 'uses' => 'RssController@download']);

    });
});
