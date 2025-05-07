<?php

// routes

use App\Controllers\HomeController;
use App\Controllers\UserController;
use App\Controllers\DashboardController;
use Core\Middleware\CsrfVerifier;
use Core\Middleware\AuthMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Route;


Route::get('/', [HomeController::class, 'index']);

Route::get('/users', [UserController::class, 'index']);

Route::group(['middleware' => [CsrfVerifier::class]], function () {
    Route::post('/users/store', [UserController::class, 'store']);
});

Route::group(['middleware' => [AuthMiddleware::class]], function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
});