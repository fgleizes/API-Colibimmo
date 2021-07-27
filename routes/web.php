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


// Pour générer une clée aléatoire à copier dans .env APP_KEY
// if (!app()->environment('prod'))     $router->get('/key', function () {
//     return 'APP_KEY=base64:' . base64_encode(\Illuminate\Support\Str::random(32));
// });