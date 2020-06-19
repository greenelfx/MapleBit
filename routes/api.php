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
    Route::get('{article}', 'ArticleController@show');

    Route::group(['middleware' => ['auth:sanctum', 'role:admin|moderator']], function() {
        Route::post('store', 'ArticleController@store');
        Route::put('update/{article}', 'ArticleController@update');
        Route::delete('{article}', 'ArticleController@destroy');
    });
});