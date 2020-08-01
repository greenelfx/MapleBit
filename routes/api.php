<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('create', 'AuthController@create');
    Route::get('devices', 'AuthController@getUserDevices');
    Route::post('revokeAll', 'AuthController@revokeAll');
});

Route::group(['prefix' => 'articles'], function () {
    Route::get('list/{category?}', 'ArticleController@list');
    Route::get('view/{article}', 'ArticleController@show');

    Route::group(['middleware' => ['auth:sanctum', 'role:admin|moderator']], function () {
        Route::post('store', 'ArticleController@store');
        Route::put('update/{article}', 'ArticleController@update');
        Route::delete('delete/{article}', 'ArticleController@destroy');
    });
});

Route::group(['prefix' => 'user', 'middleware' => ['auth:sanctum']], function () {
    Route::post('disconnect', 'AccountController@disconnectAccount');
    Route::post('update', 'AccountController@updateAccount');
    Route::get('me', 'AccountController@getMe');

    Route::group(['prefix' => 'profile'], function () {
        Route::post('store', 'ProfileController@store');
        Route::get('view/{profile_name}', 'ProfileController@get');
        Route::get('list/{profile_name?}', 'ProfileController@list');
    });
});

Route::get('serverInfo', 'PrestartController@serverInfo');
