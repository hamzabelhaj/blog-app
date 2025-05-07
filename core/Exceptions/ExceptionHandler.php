<?php
declare(strict_types=1);

namespace Core\Exceptions;

use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;


class ExceptionHandler
{
    //handle Routes errors
    public function handleError(\Throwable $error): void
    {
        if ($error instanceof NotFoundHttpException) {
            http_response_code(404);
            echo '404 - Page not found';
        } else {
            http_response_code(500);
            echo 'An unexpected error occurred: ' . $error->getMessage();
        }

        error_log($error->getMessage());
    }
}