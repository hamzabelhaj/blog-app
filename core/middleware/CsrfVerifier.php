<?php

declare(strict_types=1);
namespace Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class CsrfVerifier implements IMiddleware
{
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
