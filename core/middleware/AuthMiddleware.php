<?php

namespace Core\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;

class AuthMiddleware implements IMiddleware
{
    private ?string $role;

    public function __construct(string $role = null)
    {
        $this->role = $role;
    }

    public function handle(Request $request): void
    {
        if (!isset($_SESSION['user_id'])) { //if user is not logged in
            header('Location:' . url('login'));
            exit;
        } 
        // If role check is required(normal user or admin)
        if ($this->role !== null && ($_SESSION['role'] ?? '') !== $this->role) {
            http_response_code(403);
            exit('Access denied');
        }
    }
}