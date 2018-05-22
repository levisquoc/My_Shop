<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['widget', 'pagespeed']], function () {

    Route::get('/', ['as' => 'index', 'uses' => 'HomeController@index']);


    /**
     * Route navigation
     */

    Route::get('/{slug}.html', ['as' => 'navigation', 'uses' => 'HomeController@Navigation']);

    Route::get('{cate}/{slug}.html', ['as' => 'blogDetail', 'uses' => 'HomeController@blogDetail']);

    Route::name('email.subcrible')->post('email/subcrible', 'HomeController@emailSubcrible');

    Route::name('contact')->post('contact', 'HomeController@contact');

});