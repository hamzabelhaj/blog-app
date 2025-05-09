<?php

namespace Core;

use Pecee\SimpleRouter\SimpleRouter as Router;
use Core\Exceptions\ExceptionHandler;


class Application
{
    public function __construct()
    {
        // Start session
        if (!session_id()) {
            session_start();
        }

        // Load global helpers (e.g. csrf_field, url, etc.)
        require_once __DIR__ . '/helpers.php';

        require_once __DIR__ . '/../config/constants.php';

        // Load routes
        require_once __DIR__ . '/../routes/web.php';
    }

    public function run(): void
    {
        try {
            $request = Router::request();
            $request->setRewriteUrl(str_replace('/blog-app/public', '', $_SERVER['REQUEST_URI'] ?? '/'));
            Router::setDefaultNamespace('\App\Controllers'); //specifies default namespace for all route callbacks to avoid repitition
            Router::start();
        } catch (\Throwable $error) {
            // Fallback to your custom exception handler
            (new ExceptionHandler())->handleError(Router::request(), $error);
        }
    }
}
