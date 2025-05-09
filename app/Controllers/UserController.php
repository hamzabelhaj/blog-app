<?php

declare(strict_types=1);

namespace App\Controllers;

use League\Plates\Engine;
use Core\Database;

class UserController
{

    public function index(): void
    {

        $template = new Engine('../app/Views');
        echo $template->render('users/index', ['title' => 'User List']);
    }

    public function store(): void
    {
        $pdo = Database::connection();
        $stmt = $pdo->query('SELECT NOW()');
        $currentTime = $stmt->fetchColumn();

        echo "✅ Database connection successful!<br>";
        echo "Current DB time: " . $currentTime;
    }
}
