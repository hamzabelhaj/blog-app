<?php

declare(strict_types=1);

namespace App\Controllers;

use Config\Database;
use League\Plates\Engine;
use Respect\Validation\Validator as v;

class RegisterController
{
    public function registerUser(): void
    {
        header('Content-Type: application/json');

        $data = $_POST;

        // CSRF check
        if (!isset($data['csrf_token']) || $data['csrf_token'] !== $_SESSION['csrf_token']) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid CSRF token']);
            return;
        }

        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        $errors = [];

        if (!v::alnum()->noWhitespace()->length(3, 20)->validate($username)) {
            $errors[] = 'Username must be alphanumeric and 3-20 characters.';
        }

        if (!v::email()->validate($email)) {
            $errors[] = 'Invalid email format.';
        }

        if (!v::length(6)->validate($password)) {
            $errors[] = 'Password must be at least 6 characters.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['error' => $errors]);
            return;
        }

        $pdo = Database::connection();
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email OR username = :username");
        $stmt->execute(['email' => $email, 'username' => $username]);

        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['error' => 'User already exists']);
            return;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:u, :e, :p)");
        $stmt->execute([
            'u' => $username,
            'e' => $email,
            'p' => $hashedPassword,
        ]);

        echo json_encode(['success' => true]);
    }

    public function index(): void
    {

        $template = new Engine('../app/Views');

        echo $template->render('users/register', [
            'title' => 'Login',
            'csrf_token' => $_SESSION['csrf_token'] ?? '',
        ]);
    }
}
