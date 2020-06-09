<?php
use Illuminate\Support\Str;
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

$router->get('/key', function () {
    return Str::random(32);
});

// Router Login Employee
$router->post('login', 'Api\EmployeeController@Login');
$router->post('register', 'Api\EmployeeController@Register');

$router->group(['prefix' => 'api', 'middleware' => 'login' ], function () use ($router) {

    // Router Api Employee
    $router->get('employee', 'Api\EmployeeController@index');
    $router->get('employee/{id}', 'Api\EmployeeController@show');
    $router->put('employee/{id}', 'Api\EmployeeController@update');
    $router->delete('employee/{id}', 'Api\EmployeeController@destroy');


    // Router Api News
    $router->get('news', 'Api\NewsController@index');
    $router->get('news/{id}', 'Api\NewsController@show');
    $router->post('news', 'Api\NewsController@store');
    $router->put('news/{id}', 'Api\NewsController@update');
    $router->delete('news/{id}', 'Api\NewsController@destroy');

    // Router Api Attendece
    $router->get('attendence', 'Api\AttendenceController@index');
    $router->get('attendence/{id}', 'Api\AttendenceController@show');
    $router->post('attendence', 'Api\AttendenceController@store');
    $router->put('attendence/{id}', 'Api\AttendenceController@update');
    $router->delete('attendence/{id}', 'Api\AttendenceController@destroy');

    // Router Api Todo
    $router->get('todo', 'Api\TodoController@index');
    $router->get('todo/{id}', 'Api\TodoController@show');
    $router->post('todo', 'Api\TodoController@store');
    $router->put('todo/{id}', 'Api\TodoController@update');
    $router->delete('todo/{id}', 'Api\TodoController@destroy');

});
