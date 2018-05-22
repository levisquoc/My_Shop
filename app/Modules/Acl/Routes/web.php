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

Route::group(['prefix' => 'admin/', 'middleware' => 'admin', 'as' => 'acl.'], function () {
    /*****************
     * Admins routes *
     *****************/
    Route::resource('admins', 'AdminController');
    Route::group(['as' => 'admins.'], function () {

        //Get admins server side route
        Route::get('getAdmins', ['as' => 'serverside.getadmins', 'uses' => 'AdminController@getdata']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'AdminController@deleteSelected']);
    });

    /***************
     * Roles routes *
     ***************/
    Route::resource('roles', 'RoleController');
    Route::group(['as' => 'roles.', 'prefix' => 'roles'], function () {

        //Get admins server side route
        Route::get('server_side/getRoles', ['as' => 'serverside.getroles', 'uses' => 'RoleController@getdata']);

        //Deleted selected item
        Route::get('action/detele_selected', ['as' => 'deleteSelected', 'uses' => 'RoleController@deleteSelected']);
    });

    /********************
     * Permissons routes *
     ********************/
    Route::resource('permissions', 'PermissionController');
    Route::group(['as' => 'permissions.', 'prefix' => 'permissions'], function () {

        //Get admins server side route
        Route::get('server_side/getPermissions',
            ['as' => 'serverside.getpermissions', 'uses' => 'PermissionController@getdata']);

        //Deleted selected item
        Route::get('action/detele_selected',
            ['as' => 'deleteSelected', 'uses' => 'PermissionController@deleteSelected']);
    });
    //Edit profile
    Route::get('{id}/profile', ['as' => 'editProfile', 'uses' => 'AdminController@profile']);
});
