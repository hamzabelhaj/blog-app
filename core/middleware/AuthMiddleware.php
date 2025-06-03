<?php


/**
 * AuthMiddleware class
 * Middleware to restrict access to routes requiring authentication.
 */

namespace Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    /**
     * Handle the request and redirects non authenticated users.
     * 
     * @param Request $request The incoming HTTP request instance.
     * @return void
     */
    public function handle(Request $request): void
    {
        if (!isset($_SESSION['user']['id'])) {
            header('Location:' . url('login'));
            exit();
        }
    }
}
