<?php

use Illuminate\Http\Request;

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function($api){

    // endpoint Авторизации
   $api->post('login', 'App\Http\Controllers\Api\Auth\LoginController@login');
   // endpoint Регистрации
   $api->post('register', 'App\Http\Controllers\Api\Auth\RegisterController@register');
  // Endpoint Профайл
   $api->group(['middleware' => 'api.auth'], function ($api){
        $api->get('user', 'App\Http\Controllers\Api\UsersController@index');
    });

});

