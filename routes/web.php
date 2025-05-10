<?php

// routes

use App\Controllers\DashboardController;
use Core\Middleware\CsrfVerifier;
use Core\Middleware\AuthMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;


Router::get('/', 'HomeController@index')->name('home');; //accesses Home Controller and invokes index funtion

Router::get('/users', 'UserController@index')->name('users');


Router::get('/register', 'RegisterController@index');
Router::get('/login', 'LoginController@index');


Router::post('/register', 'RegisterController@registerUser');
Router::post('/login', 'LoginController@loginUser');

Router::get('/users/db-test', 'UserController@store')->name('store');;

/*Router::group(['middleware' => [CsrfVerifier::class]], function () {
    Router::post('/users/store', 'UserController@store');
});*/

Router::group(['middleware' => [AuthMiddleware::class]], function () {
    Router::get('/dashboard', 'DashboardController@index');
});
