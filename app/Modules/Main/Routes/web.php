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

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/login', ['as' => 'login', 'uses' => 'AdminAuthController@showLoginForm']);
    Route::post('/login', ['as' => 'postlogin', 'uses' => 'AdminAuthController@login']);

    // Show recovery password form
    Route::post('password/email', ['uses' => 'ResetPassController@postforgotpass', 'as' => 'password.sendmail']);
    Route::get('password/reset/{token}', ['uses' => 'ResetPassController@resetpassword', 'as' => 'password.token']);
    Route::post('password/reset', ['uses' => 'ResetPassController@postresetpassword', 'as' => 'password.reset']);

    Route::get('/', ['as' => 'dashboard', 'uses' => 'MainController@index']);
    Route::get('/logout', ['as' => 'logout', 'uses' => 'MainController@logout']);
});
