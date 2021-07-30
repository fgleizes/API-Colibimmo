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
    $router->get('agency/{idAgency}', 'PersonController@showAllByRole');
    $router->put('{id}', 'PersonController@update');
    $router->delete('{id}', 'PersonController@delete');
});

$router->post('agency', 'AgencyController@create');
$router->get('agency', 'AgencyController@show');
$router->delete('agency/{id}', 'AgencyController@delete');
$router->get('agency/{id}', 'AgencyController@oneShow');
$router->put('agency/{id}', 'AgencyController@update');

$router->get('role', 'RoleController@show');
$router->get('role/{id}', 'RoleController@oneShow');
$router->put('role/{id}', 'RoleController@update');





// Pour générer une clée aléatoire à copier dans .env APP_KEY
// if (!app()->environment('prod'))     $router->get('/key', function () {
//     return 'APP_KEY=base64:' . base64_encode(\Illuminate\Support\Str::random(32));
// });