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

    $router->post('/', 'AddressController@create');
    $router->put('{id}','AddressController@update');
    $router->delete('{id}','AddressController@delete');
    $router->get('showByCity/{name}','AddressController@showAddressesByCity');
    $router->get('showByPerson/{id}','AddressController@showAddressByPerson');
    $router->get('cities', 'AddressController@showCities');
    $router->get('{id}', 'AddressController@showAdress');
    $router->get('/','AddressController@showAdresses');
});


/**
 * Routes Project
 */
$router->group([
    'prefix' => 'project'

], function () use ($router) {
    $router->post('/', 'ProjectController@create');
    $router->put('{id}', 'ProjectController@update');
    $router->delete('{id}','ProjectController@delete');
    $router->get('{id}', 'ProjectController@showOne');
    $router->get('/','ProjectController@show');
});

/**
 * Routes Appointment
 */
$router->group([
    'prefix' => 'appointment'

], function () use ($router) {
    $router->post('/', 'AppointmentController@create');
    $router->put('/{id}', 'AppointmentController@update');
    $router->delete('{id}', 'AppointmentController@delete');
    $router->get('{id}','AppointmentController@showOne');
    $router->get('/','AppointmentController@show');
});

/**
 * Routes Room
 */
$router->group([
    'prefix' => 'typeRoom'

], function () use ($router) {
    $router->post('/', 'RoomController@createType');
    $router->put('{id}', 'RoomController@updateType');
    $router->delete('{id}', 'RoomController@deleteType');
    $router->get('{id}', 'RoomController@showOneType');    
    $router->get('/', 'RoomController@showType');   
});

$router->group([
    'prefix' => 'room'

], function () use ($router) {
    $router->post('/', 'RoomController@createRoom');
    $router->put('{id}', 'RoomController@updateRoom');
    $router->delete('{id}', 'RoomController@deleteRoom');  
    $router->get('{id}', 'RoomController@showOneRoom');
    $router->get('/', 'RoomController@showRoom');
    
});

$router->group([
    'prefix' => 'option'

], function () use ($router) {
    $router->post('/', 'OptionController@create');
    $router->put('{id}', 'OptionController@update');
    $router->get('/', 'OptionController@show');
    $router->get('{id}', 'OptionController@showOne');
    $router->delete('{id}', 'OptionController@delete');
   
    
});

$router->group([
    'prefix' => 'optionProject'

], function () use ($router) {
    $router->post('/', 'OptionController@createOptionProject');
    $router->get('/', 'TypePropertyController@showOptionProject');
    $router->get('{id}', 'TypePropertyController@showOneOptionProject');
    
   
    
});

$router->group([
    'prefix' => 'typeProperty'

], function () use ($router) {
    $router->post('/', 'TypePropertyController@create');
    $router->put('{id}', 'TypePropertyController@update');
    $router->get('/', 'TypePropertyController@show');
    $router->get('{id}', 'TypePropertyController@showOne');
    $router->delete('{id}', 'TypePropertyController@delete');
   
    
});



