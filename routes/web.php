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
    $router->get('/', 'PersonController@show');
    $router->get('{id}', 'PersonController@showOne');
    $router->get('role/{idRole}', 'PersonController@showByRole');
    $router->get('agency/{idAgency}', 'PersonController@showByAgency');
});

/**
 * Routes agency
 */
$router->group([
    'prefix' => 'agency'

], function () use ($router) {
    
    $router->post('/create', 'AgencyController@create');
    $router->put('/update/{id}', 'AgencyController@update');
    $router->delete('/delete/{id}', 'AgencyController@delete');
    $router->get('/', 'AgencyController@show');
    $router->get('{id}', 'AgencyController@showOne');
});

/**
 * Routes role
 */
$router->group([
    'prefix' => 'role'

], function () use ($router) {

    $router->put('{id}', 'RoleController@update');
    $router->get('/', 'RoleController@show');
    $router->get('{id}', 'RoleController@oneShow');
});

/**
 * Routes Address
 */
$router->group([
    'prefix' => 'address'

], function () use ($router) {

    $router->post('/', 'AddressController@create');
    $router->put('/{id}','AddressController@update');
    $router->delete('{id}','AddressController@delete');
    $router->get('/','AddressController@showAdresses');
    $router->get('{id}', 'AddressController@showAdress');
    $router->get('showByCity/{name}','AddressController@showAddressesByCity');
    $router->get('showByPerson/{id}','AddressController@showAddressByPerson');
});

/**
 * Routes Appointment
 */
$router->group([
    'prefix' => 'appointment'

], function () use ($router) {
    $router->post('create', 'AppointmentController@create');
    $router->put('/{id}', 'AppointmentController@update');
    $router->delete('/delete/{id}', 'AppointmentController@delete');
    $router->post('type_appointment', 'typeAppointmentController@create');
    $router->get('/','AppointmentController@show');
    $router->get('show/{id}','AppointmentController@showOne');
});