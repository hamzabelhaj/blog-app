<?php

namespace App\Controllers;

use Config\Database;
use League\Plates\Engine;

class LoginController
{
    public function login(): void
    {
        header('Content-Type: application/json');

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo json_encode(['success' => true]);
    }

    public function index(): void
    {

        $template = new Engine('../app/Views');

        echo $template->render('users/login', [
            'title' => 'Login',
            'csrf_token' => $_SESSION['csrf_token'] ?? '',
        ]);
    }
}
