<?php

// routes

use App\Controllers\DashboardController;
use Core\Middleware\CsrfVerifier;
use Core\Middleware\AuthMiddleware;
use Core\Middleware\GuestMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;


Router::get('/', 'DashboardController@index')->name('dashboard');; //accesses Home Controller and invokes index funtion
Router::get('/register', 'RegisterController@index');
Router::get('/login', 'LoginController@index');


Router::group(['middleware' => [CsrfVerifier::class]], function () {
    Router::post('/register', 'RegisterController@registerUser');
    Router::post('/login', 'LoginController@loginUser');
});

// For general logged-in users
Router::group(['middleware' => [AuthMiddleware::class]], function () {
    Router::get('/dashboard', 'DashboardController@index');
});

// For admin-only routes
Router::group(['middleware' => fn() => new AuthMiddleware('admin')], function () {
    Router::get('/admin', 'AdminController@index');
});



Router::group(['middleware' => [GuestMiddleware::class]], function () {
    Router::get('/login', 'LoginController@index');
    Router::get('/register', 'RegisterController@index');
    Router::post('/login', 'LoginController@loginUser');
    Router::post('/register', 'RegisterController@registerUser');
});
