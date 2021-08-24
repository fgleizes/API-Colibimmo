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

    $router->put('{id}', 'PersonController@update');
    $router->delete('{id}', 'PersonController@delete');
    $router->get('agency/{idAgency}', 'PersonController@showByAgency');
    $router->get('role/{idRole}', 'PersonController@showByRole');
    $router->get('{id}', 'PersonController@showOne');
    $router->get('/', 'PersonController@show');
});

/**
 * Routes agency
 */
$router->group([
    'prefix' => 'agency'

], function () use ($router) {
    
    $router->post('/', 'AgencyController@create');
    $router->put('{id}', 'AgencyController@update');
    $router->delete('{id}', 'AgencyController@delete');
    $router->get('{id}', 'AgencyController@showOne');
    $router->get('/', 'AgencyController@show');
});

/**
 * Routes role
 */
$router->group([
    'prefix' => 'role'

], function () use ($router) {

    $router->put('{id}', 'RoleController@update');
    $router->get('{id}', 'RoleController@oneShow');
    $router->get('/', 'RoleController@show');
});

/**
 * Routes Address
 */
$router->group([
    'prefix' => 'address'

], function () use ($router) {

    // $router->post('/', 'AddressController@create');
    // $router->put('{id}','AddressController@update');
    // $router->delete('{id}','AddressController@delete');
    // $router->get('showByCity/{name}','AddressController@showAddressesByCity');
    // $router->get('showByPerson/{id}','AddressController@showAddressByPerson');
    // $router->get('cities', 'AddressController@showCities');
    // $router->get('{id}', 'AddressController@showAdress');
    // $router->get('/','AddressController@showAdresses');
});


/**
 * Routes Project
 */
$router->group([
    'prefix' => 'project'

], function () use ($router) {
    $router->post('/', 'ProjectController@create');
    $router->put('{id}', 'ProjectController@update');
    $router->delete('{id}','ProjectController@destroy');
    $router->get('{id}', 'ProjectController@showOne');
    $router->get('/','ProjectController@show');
});

/**
 * Routes Appointment
 */
$router->post('appointment', 'AppointmentController@create');