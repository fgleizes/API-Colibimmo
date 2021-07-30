<?php

use Illuminate\Support\Facades\Route;

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
 * Routes user liées à l'authentification
 */
$router->group([
    'prefix' => 'user'

], function () use ($router) {
    
    $router->post('register', 'AuthController@register');
    $router->get('login', 'AuthController@login');
    $router->get('logout', 'AuthController@logout');
    $router->get('refresh', 'AuthController@refresh');
    $router->get('me', 'AuthController@me');
});

/**
 * Routes person liées aux utilisateurs/clients et employés
 */
$router->group([
    'prefix' => 'person'

], function () use ($router) {

    $router->get('{id}', 'PersonController@showOne');
    $router->get('/', 'PersonController@showAll');
    $router->get('role/{idRole}', 'PersonController@showAllByRole');
    $router->get('agency/{idAgency}', 'PersonController@showAllByAgency');
    $router->put('{id}', 'PersonController@update');
    $router->delete('{id}', 'PersonController@delete');
});

/**
 * Routes agency
 */
$router->group([
    'prefix' => 'agency'

], function () use ($router) {
    
    $router->post('/', 'AgencyController@create');
    $router->get('/', 'AgencyController@show');
    $router->delete('{id}', 'AgencyController@delete');
    $router->get('{id}', 'AgencyController@oneShow');
    $router->put('{id}', 'AgencyController@update');
});

/**
 * Routes role
 */
$router->group([
    'prefix' => 'role'

], function () use ($router) {

    $router->get('/', 'RoleController@show');
    $router->get('{id}', 'RoleController@oneShow');
    $router->put('{id}', 'RoleController@update');
});

/**
 * Routes Address
 */
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