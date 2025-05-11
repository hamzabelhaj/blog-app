<?php

namespace Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use Core\Middleware\AuthMiddleware;
use Core\Middleware\GuestOnlyMiddleware;

class GuestMiddleware implements IMiddleware
{
    public function handle(Request $request): void
    {
        session_start();

        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
    }
}