<?php
namespace Core;
use Pecee\SimpleRouter\SimpleRouter as Route;
use Core\Exceptions\ExceptionHandler;


class Application{
    public function __construct()
    {
        // Start session
        if (!session_id()) {
            session_start();
        }

        // Load global helpers (e.g. csrf_field, url, etc.)
        require_once __DIR__ . '/helpers.php';

        // Load routes
        require_once __DIR__ . '/../routes/web.php';
    }

    public function run(): void
    {
        try {
            Route::setDefaultNamespace('\App\Controllers');//specifies default namespace for all route callbacks to avoid repitition
            Route::start();
        } catch (\Throwable $error) {
            // Fallback to your custom exception handler
            (new ExceptionHandler())->handleError($error);
        }
    }

}


