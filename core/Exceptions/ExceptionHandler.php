<?php

/**
 * ExceptionHandler Class
 *
 * Centralized handler for uncaught exceptions and routing errors.
 * Differentiates between API and web requests, and responds with
 * appropriate status codes and messages.
 */

declare(strict_types=1);

namespace Core\Exceptions;

use Pecee\Http\Request;
use Pecee\SimpleRouter\Exceptions\NotFoundHttpException;



class ExceptionHandler
{
    /**
     * Handles application routing exceptions.
     *
     * Determines the type of error and request context (API vs. Web), then outputs an appropriate HTTP response
     *
     * @param null|Request $request The current HTTP request
     * @param \Throwable $error The exception or error that was thrown
     *
     * @return void
     */
    public function handleError(Request $request = null, \Throwable $error): void
    {

        if ($request->getUrl()->contains('/api')) { 
            echo json_encode([
                'error' => $error->getMessage(),
                'code' => $error->getCode(),
            ]);
        }

        if ($error instanceof NotFoundHttpException) {
            http_response_code(404);
            exit('404: Page not found');
        } else { //internal server error
            http_response_code(500);
            echo 'An unexpected error occurred: ' . $error->getMessage();
            exit();
        };
    }
}
