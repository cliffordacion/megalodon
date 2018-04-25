<?php

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

$router->group(
   [  
      'middware' => 'api_key',
      'prefix' => 'api/v1',
      'namespace' => '\App\Http\Controllers'
   ],
   function ($router) {
      $router->get('user', 'UserController@index');
      $router->get('user/{id}', 'UserController@get');
      $router->post('user', 'UserController@create');
      $router->put('user/{id}', 'UserController@update');
      $router->delete('user/{id}', 'UserController@delete');
      $router->get('user/{id}/location', 'UserController@getCurrentLocation');
      $router->post('user/{id}/location/latitude/{latitude}/longitude/{longitude}',
         'UserController@setCurrentLocation'
      );
   }
);