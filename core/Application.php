<?php

/**
 * Core Application class
 * Handles loading necessary files such as helpers and routes and starts the router
 */


namespace Core;

use Pecee\SimpleRouter\SimpleRouter as Router;
use Core\Exceptions\ExceptionHandler;


class Application
{
    
    public function __construct()
    {
        if (!session_id()) {
            session_start();
        }
        require_once __DIR__ . '/helpers.php'; //global helpers
        require_once __DIR__ . '/../config/constants.php'; //global constants
        require_once __DIR__ . '/../routes/web.php'; //routes
    }

    /**
     * Runs the application by starting the router
     *
     * @return void
     */
    public function run(): void
    {
        try {
            $request = Router::request();
            $request->setRewriteUrl(str_replace('/blog-app/public', '', $_SERVER['REQUEST_URI'] ?? '/')); //rewrites the base path
            Router::setDefaultNamespace('\App\Controllers'); //specifies default namespace for all route callbacks to avoid repitition
            Router::start();
        } catch (\Throwable $error) {
            // Fallback to custom exception handler
            (new ExceptionHandler())->handleError(Router::request(), $error);
        }
    }
}
