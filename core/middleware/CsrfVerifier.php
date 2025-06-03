<?php

/**
 * CsrfVerifier Class
 *
 * Middleware to protect against Cross-Site Request Forgery (CSRF) attacks.
 * Validates the CSRF token on incoming POST requests.
 */

declare(strict_types=1);

namespace Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class CsrfVerifier implements IMiddleware
{
    /**
     * Handles the request and verifies the incoming CSRF token via POST request with the one stored in the session.
     *
     * @param Request $request The incoming HTTP request instance.
     * @return void
     */
    public function handle(Request $request): void
    {
        if ($request->getMethod() === 'post') {
            $token = $_POST['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            if ($token !== $sessionToken) {
                http_response_code(403); // forbidden
                exit('Invalid CSRF token');
            }
        }
    }
}
