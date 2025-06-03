<?php

/**
 * RegisterController Class
 * Handles user registeration.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use Respect\Validation\Validator as v;

class RegisterController extends BaseController
{

    /**
     * Registers new users
     *
     * @return void
     */
    public function registerUser(): void
    {

        header('Content-Type: application/json');

        //sanitization
        $username = strip_tags(trim($_POST['username'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $repPassword =  $_POST['repeatedPassword'];
        $errors = [];
        //validate username
        if (!v::notEmpty()->validate($username)) {
            $errors[] = 'Username cannot be empty.';
        } elseif (!v::alnum()->noWhitespace()->length(3, 20)->validate($username)) {
            $errors[] = 'Username must be alphanumeric and 3–20 characters.';
        }

        //validate email
        if (!v::notEmpty()->validate($email)) {
            $errors[] = 'Email cannot be empty.';
        } elseif (!v::email()->validate($email)) {
            $errors[] = 'Invalid email format.';
        }
        //validate password
        if (!v::notEmpty()->validate($password) || !v::notEmpty()->validate($repPassword)) {
            $errors[] = 'Password and repeated password cannot be empty.';
        } elseif ($password !== $repPassword) {
            $errors[] = 'Passwords do not match.';
        } else {
            $regexRules = v::allOf(
                v::length(6, null),
                v::regex('/[A-Z]/')->setName('uppercase'),
                v::regex('/[a-z]/')->setName('lowercase'),
                v::regex('/[0-9]/')->setName('digit'),
                v::regex('/[\W]/')->setName('special char') // \W = non-word char
            );
            if (!$regexRules->validate($password)) {
                $errors[] = 'Password must be at least 6 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
            }
        }
        if ($errors) {
            http_response_code(422);
            echo json_encode(['error' => $errors]);
            return;
        }
        $user = new UserModel();
        //check for duplicate credentials
        if ($user->getUser($email, $username)) {
            http_response_code(409); //
            echo json_encode(['error' => 'Another User exists with the same credentials. Choose another username or email.']);
            return;
        }

        $user->createUser($username, $email, $password);

        echo json_encode(['success' => true]);
    }

    /**
     * Renders register view
     *
     * @return void
     */
    public function renderRegisterView(): void
    {
        $this->view(
            'register',
            [
                'title' => 'Register Page',
            ]
        );
    }
}
