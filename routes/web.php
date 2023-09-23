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

$router->group([
    'prefix' => 'api',
    'middleware' => ['appCodeValidate']
], function () use ($router) {
    $router->get('users', 'UserAdminController@index');
    $router->post('users', 'UserAdminController@store');
    $router->get('users/{id}', 'UserAdminController@show');
    $router->put('users/{id}', 'UserAdminController@update');
    $router->delete('users/{id}', 'UserAdminController@destroy');
});
