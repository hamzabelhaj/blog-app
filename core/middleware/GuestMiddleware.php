<?php

/**
 * GuestMiddleware class
* Middleware to restrict authenticated users from accessing guest-only routes.
 */

namespace Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;


class GuestMiddleware implements IMiddleware
{
    /**
     * Handle the request and redirect authenticated users.
     * 
     * @param Request $request The incoming HTTP request instance.
     * @return void
     */
    public function handle(Request $request): void
    {
        if (isset($_SESSION['user']['id'])) {
            header('Location: ' . url('/'));
            exit;
        }
    }
}
