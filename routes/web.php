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

/**
 * Routes User
 */

$router->group([
    'prefix' => 'user'

], function () use ($router) {
    
    $router->post('register', 'AuthController@register');
    $router->get('login', 'AuthController@login');
    $router->get('logout', 'AuthController@logout');
    $router->get('refresh', 'AuthController@refresh');
    $router->get('profile', 'AuthController@profile');
});

$router->group([
    'prefix' => 'address'

], function () use ($router) {

    $router->post('/', 'AddressController@create');
    $router->delete('{id}','AddressController@delete');
    $router->put('/{id}','AddressController@update');
    $router->get('/','AddressController@show');
    $router->get('showByCity/{name}','AddressController@showByCity');
    $router->get('showByPerson/{id}','AddressController@showByPerson');
    $router->get('{id}', 'AddressController@oneShow');
});