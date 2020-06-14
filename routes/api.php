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