<?php

/**
 * ProfileController Class
 * Handles user's profile related operations such as displaying profile, editing, and deleting.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\DashboardController;
use App\Models\UserModel;
use Respect\Validation\Validator as v;

class ProfileController extends DashboardController
{

    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Renders User's Profile subview on the dashboard view
     *
     * @return void
     */
    public function showProfile(): void
    {
        $this->renderDashboard(
            'dashboard/profile/show',
            [
                'title' => 'Profile Page'

            ]
        );
    }

    /**
     * Renders User's Profile edit subview on the dashboard view
     *
     * @return void
     */
    public function editProfile(): void
    {
        $this->renderDashboard(
            'dashboard/profile/edit', //subview 
            [
                'title' => 'Edit Profile Page',
            ]
        );
    }
    /**
     * Updates User's Profile
     *
     * @return void
     */
    public function updateProfile(): void
    {
        //check if current user is not authenticated
        if (!isset($_SESSION['user']['id'])) {
            header('Location:' .  url('login'));
            exit();
        }
        header('Content-Type: application/json');

        if ($_POST['method'] !== 'UPDATE') {
            http_response_code(405);
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }

        $username = strip_tags(trim($_POST['username'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';
        $errors = [];
        //validate  username
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
        $currentUserId = $_SESSION['user']['id'] ?? null;
        $currentUser = $this->userModel->getUserById($currentUserId);
        //validate password if present
        if (!empty($password)) {
            $regexRules = v::allOf(
                v::length(6, null),
                v::regex('/[A-Z]/')->setName('uppercase'),
                v::regex('/[a-z]/')->setName('lowercase'),
                v::regex('/[0-9]/')->setName('digit'),
                v::regex('/[\W]/')->setName('special char')
            );
            if (!$regexRules->validate($password)) {
                $errors[] = 'Password must be at least 6 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
            } elseif (password_verify($password, $currentUser['password'])) {
                $errors[] = 'New password matches the current password. Choose a new password.';
            }
        }
        //no changes
        if ($currentUser['username'] === $username && $currentUser['email'] === $email && empty($password)) {
            $errors[] = 'No changes were made: Choose another Username or email.';
        }
        if ($errors) {
            http_response_code(422);
            echo json_encode(['error' => $errors]);
            return;
        }
        $existingUser = $this->userModel->getUser($email, $username);
        //check for duplicate credentials
        if ($existingUser && $existingUser['id'] !== $currentUserId) {
            http_response_code(409);
            echo json_encode(['error' => 'Another User exists with the same credentials. Choose another username or email.']);
            return;
        }
        $this->userModel->updateUser($currentUserId, $username, $email, $password);

        if ($username !== $currentUser['username']) { //if username is updated
            $_SESSION['user']['username'] = $username;
        }
        if ($email !== $currentUser['email']) { //if email is updated
            $_SESSION['user']['email'] = $email;
        }
        echo json_encode(['success' => true]);
    }
    /**
     * Deletes User's Profile
     *
     * @return void
     */
    public function deleteProfile(): void
    {
        //check if current user is not authenticated
        if (!isset($_SESSION['user']['id'])) {
            header('Location:' .  url('login'));
            exit();
        }
        //check if current user is an admin
        if (($_SESSION['user']['role'] ?? '') === 'admin') {
            http_response_code(403);
            exit('Access denied');
        }
        header('Content-Type: application/json');
        //check post method
        if ($_POST['method'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }
        $userId = $_SESSION['user']['id'] ?? null;
        //check if user exists
        if (!$this->userModel->getUserById($userId)) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }
        $this->userModel->deleteUser($userId);
        echo json_encode(['success' => true]);
    }
}
