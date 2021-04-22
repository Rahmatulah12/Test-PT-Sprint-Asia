<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(["prefix" => "api"], function() use($router){

    $router->post('registration', ['as' => 'register', 'uses' => "LoginController@registration"]);
    $router->post('login', ['as' => 'login', 'uses' => "LoginController@login"]);

    $router->group(['middleware' => 'auth'], function() use($router){

        $router->post('logout', ['as' => 'logout', 'uses' => 'LoginController@logout']);

        $router->group(['prefix' => 'menu'], function() use($router){
            $router->get('/', ['as' => 'menu.index', 'uses' => 'MenuController@index']);
            $router->post('/', ['as' => 'menu.post', 'uses' => 'MenuController@store']);
            $router->patch('/', ['as' => 'menu.update', 'uses' => 'MenuController@update']);
            $router->get('/{id}', ['as' => 'menu.detail', 'uses' => 'MenuController@show']);
            $router->delete('/{id}', ['as' => 'menu.delete', 'uses' => 'MenuController@delete']);
        });

        $router->group(['prefix' => 'order'], function() use($router){
            $router->get('/', ['as' => 'order.index', 'uses' => 'OrderController@index']);
            $router->get('/{id}', ['as' => 'order.detail', 'uses' => 'OrderController@show']);
            $router->delete('/{id}', ['as' => 'order.delete', 'uses' => 'OrderController@delete']);
            $router->post('/', ['as' => 'order.post', 'uses' => 'OrderController@store']);
        });

    });

});
