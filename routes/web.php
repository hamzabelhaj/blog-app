<?php
/**
 * Routes File 
 * Handles routes based on user authentication and role permissions as well as csrf validation.
 */

use Core\Middleware\CsrfVerifier;
use Core\Middleware\AuthMiddleware;
use Core\Middleware\AdminMiddleware;
use Core\Middleware\GuestMiddleware;
use Pecee\SimpleRouter\SimpleRouter as Router;

//public routes
Router::get('/', 'HomeController@renderHomeView');
Router::get('/posts', 'PostController@listPublicPosts');
Router::get('/posts/page/{number}', 'PostController@listPublicPosts');
Router::get('/posts/{id}', 'PostController@showPost');

//Csrf verified routes
Router::group(['middleware' => [CsrfVerifier::class]], function () {
    Router::post('/register', 'RegisterController@registerUser');
    Router::post('/login', 'LoginController@loginUser');
    //posts
    Router::post('/dashboard/posts/store', 'PostController@storePost');
    Router::post('/dashboard/posts/{id}/update', 'PostController@updatePost');
    Router::post('/dashboard/posts/{id}/delete', 'PostController@deletePost');
    //profile
    Router::post('/dashboard/profile/update', 'ProfileController@updateProfile');
    Router::post('/dashboard/profile/delete', 'ProfileController@deleteProfile');
    //users
    Router::post('/dashboard/users/store', 'UserController@storeUser');
    Router::post('/dashboard/users/{id}/update', 'UserController@updateUser');
    Router::post('/dashboard/users/{id}/delete', 'UserController@deleteUser');
});
//guest routes
Router::group(['middleware' => [GuestMiddleware::class]], function () {
    Router::get('/login', 'LoginController@renderLoginView');
    Router::get('/register', 'RegisterController@renderRegisterView');
});
// Authenticated users routes
Router::group(['middleware' => [AuthMiddleware::class]], function () {
    Router::get('/logout', 'LogoutController@logoutUser');
    //dashboard/section
    Router::get('/dashboard', 'DashboardController@renderDashboard');
    //profile
    Router::get('/dashboard/profile/', 'ProfileController@showProfile');
    Router::get('/dashboard/profile/edit', 'ProfileController@editProfile');
    //dashboard posts
    Router::get('/dashboard/posts', 'PostController@listDashboardPosts');
    Router::get('/dashboard/posts/page/{number}', 'PostController@listDashboardPosts');
    Router::get('/dashboard/posts/create', 'PostController@createPost');
    Router::get('/dashboard/posts/{id}/edit', 'PostController@editPost');
});
//Admin routes
Router::group(['middleware' => [AdminMiddleware::class]], function () {
    //users
    Router::get('/dashboard/users', 'UserController@listUsers');
    Router::get('/dashboard/users/page/{number}', 'UserController@listUsers');
    Router::get('/dashboard/users/create', 'UserController@createUser');
    Router::get('/dashboard/users/{id}/edit', 'UserController@editUser');
});
