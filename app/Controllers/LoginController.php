<?php

namespace App\Controllers;


use Core\Controller;
use App\Modules\UserModule;
use Config\Database;
use League\Plates\Engine;
use Respect\Validation\Validator as v;

class LoginController extends Controller
{
    public function loginUser(): void
    {
        header('Content-Type: application/json');

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $error = '';

        // Empty input
        if (!v::notEmpty()->validate($email) || !v::notEmpty()->validate($password)) {
            $error = 'Please enter your email and password.';
            //invalid email format
        } elseif (!v::email()->validate($email)) {
            $error = 'Please enter a valid email address.';
        }

        if ($error) {
            http_response_code(422);
            echo json_encode(['error' => $error]);
            return;
        }
        $user = new UserModule();
        $userData = $user->getUserByEmail($email);
        if (!$user->exists($email) || !password_verify($password, $userData['password'])) {
            http_response_code(401); //unauthorized
            echo json_encode(['error' => 'Invalid credentials.']);
            return;
        }
        $_SESSION['user_id'] = $userData['id'];
        $_SESSION['username'] = $userData['username'];
        $_SESSION['email'] = $userData['email'];
        echo json_encode(['success' => true]);
    }

    public function index(): void
    {
        $this->view(
            'users/login',
            [
                'title' => 'login',
                'csrf_token' => $_SESSION['csrf_token'] ?? ''
            ]
        );
    }
}
