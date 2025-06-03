<?php

/**
 * AdminMiddleware class
 * Middleware to restrict access to only-admin routes.
 */

namespace Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AdminMiddleware implements IMiddleware
{
    /**
     * Handle the request and restricts access to admins only.
     * 
     * @param Request $request The incoming HTTP request instance.
     * @return void
     */
    public function handle(Request $request): void
    {
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('Access denied');
        }
    }
}
