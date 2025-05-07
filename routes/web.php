<?php

// routes

use App\Controllers\DashboardController;
use Core\Middleware\CsrfVerifier;
use Core\Middleware\AuthMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Route;


Route::get('/', 'HomeController@index'); //accesses Home Controller and invokes index funtion

Route::get('/users', 'UserController@index');

Route::group(['middleware' => [CsrfVerifier::class]], function () {
    Route::post('/users/store', 'UserController@store');
});

Route::group(['middleware' => [AuthMiddleware::class]], function () {
    Route::get('/dashboard', 'DashboardController@index');
});