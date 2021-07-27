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

/*
|--------------------------------------------------------------------------
| Routes Person
|--------------------------------------------------------------------------
*/

$router->get('user', 'AuthController@login');
$router->post('user', 'AuthController@register');

// $router->get('user', function () {
//     return 'Hello World';
// });

// $router->get('user/{id}', ['as' => 'profile', function ($id) {
//     return 'Hello User id:' . $id;
// }]);

// $router->post('user', ['as' => 'signin', function () {
//     return 'User added';
// }]);

// $url = route('signin', ['id' => 1]);

// $router->put('user/{id}', function ($id) {
//     return 'User id:' . $id . ' updated';
// });

// $router->delete('user/{id}', function ($id) {
//     return 'User id:' . $id . ' deleted';
// });

$router->group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function () use ($router) {

    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');
});

// $router->group(['middleware' => 'auth'], function () use ($router) {
//     $router->get('/', function () {
//         // Uses Auth Middleware
//     });

//     $router->get('user/profile', function () {
//         // Uses Auth Middleware
//     });
// });



// Pour générer une clée aléatoire à copier dans .env APP_KEY
// if (!app()->environment('prod'))     $router->get('/key', function () {
//     return 'APP_KEY=base64:' . base64_encode(\Illuminate\Support\Str::random(32));
// });