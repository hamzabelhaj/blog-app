<?php

// routes

use App\Controllers\DashboardController;
use Core\Middleware\CsrfVerifier;
use Core\Middleware\AuthMiddleware;
use Core\Middleware\GuestMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;

Router::group(['middleware' => [CsrfVerifier::class]], function () {
    Router::post('/register', 'RegisterController@registerUser');
    Router::post('/login', 'LoginController@loginUser');
});

// For general logged-in users
Router::group(['middleware' => [AuthMiddleware::class]], function () {
    Router::get('/', 'DashboardController@index');
});

// For admin-only routes
Router::group(['middleware' => fn() => new AuthMiddleware('admin')], function () {
    Router::get('/admin', 'AdminController@index');
});

 Router::get('/logout', 'LogoutController@logoutUser');


Router::group(['middleware' => [GuestMiddleware::class]], function () {
    Router::get('/login', 'LoginController@index');
    Router::get('/register', 'RegisterController@index');
    Router::post('/login', 'LoginController@loginUser');
    Router::post('/register', 'RegisterController@registerUser');
});
