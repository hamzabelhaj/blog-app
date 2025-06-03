<?php

/**
 * LoginController Class
 * Handles user login.
 */

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Respect\Validation\Validator as v;

class LoginController extends BaseController
{
    /**
     * Logs in users
     *
     * @return void
     */
    public function loginUser(): void
    {
        header('Content-Type: application/json');
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $error = '';
        //validate email and password
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
        $user = new UserModel();
        $userData = $user->getUserByEmail($email);
        //check for invalid credentials
        if (!$user->getUser($email) || !password_verify($password, $userData['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials.']);
            return;
        }
        $_SESSION['user'] = [
            'id' => $userData['id'],
            'username' => $userData['username'],
            'email' => $userData['email'],
            'role' => $userData['role']
        ];
        echo json_encode(['success' => true]);
    }

    /**
     * 
     * Renders login view
     *
     * @return void
     */
    public function renderLoginView(): void
    {
        $this->view(
            'login',
            [
                'title' => 'Login Page',
            ]
        );
    }
}
