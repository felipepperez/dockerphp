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

$router->post('auth', 'UsersController@auth');

$router->get('users', function () use ($router) {
    return [];
});
 
$router->get('users/{id}', function ($id) use ($router) {
    return ["id"=>$id];
});

$router->post('users', function (Request $request) use ($router) {
    return [];
});

$router->put('users/{id}', function ($id) use ($router) {
    return ["id"=>$id];
});

$router->delete('users/{id}', function ($id) use ($router) {
    //return ["id"=>$id];
    return 204;
});