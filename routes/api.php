<?php

use Illuminate\Http\Request;

Route::group(['prefix'=>'users'],function(){
    Route::get('/', 'Api\UsersController@index');
    Route::group(['middleware'=>'auth:api'],function(){
        Route::post('create', 'Api\UsersController@create');
        Route::patch('{user}/update', 'Api\UsersController@update');
        Route::delete('{user}/delete', 'Api\UsersController@delete');
    });
});


