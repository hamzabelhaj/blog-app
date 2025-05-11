<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use League\Plates\Engine;



class DashboardController extends Controller
{

    public function index(): void
    {
        $this->view(
            'dashboard',
            [
                'title' => 'dashboard',
                'csrf_token' => $_SESSION['csrf_token'] ?? ''
            ]
        );
    }
}
