<?php

/**
 * UserController Class
 * Handles user related operations such as listing users, creating, editing,
 * deleting user accounts.
 */

declare(strict_types=1);

namespace App\Controllers;

use App\Controllers\DashboardController;
use App\Models\UserModel;
use Respect\Validation\Validator as v;

class UserController extends DashboardController
{
    private UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    /**
     * Lists users on the dashboard users subview and handles also pagination requests
     *
     * @param int|null $page  The page number of users list
     *
     * @return void
     */
    public function listUsers(?int $page = null): void
    {
        //pagination
        $currentPage = $page ?? 1;
        $limit = 3;
        $offset = ($currentPage - 1) * $limit;
        $currentId = $_SESSION['user']['id'] ?? null;
        $totalUsers = $this->userModel->countUsers([['column' => 'id', 'operator' => '!=', 'value' => $currentId]]); //count all users escept current admin
        $totalPages = ceil($totalUsers / $limit);

        //check if specified page exists
        if (($currentPage > $totalPages) || ($currentPage < 1)) {
            http_response_code(404);
            exit('Page not found');
        }
        $allUsers = $this->userModel->getNonCurrentUsers($currentId, $limit, $offset); //all users from database except current admin user
        //check for pagination AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode([
                'users' => $allUsers,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages
            ]);
            return;
        }
        $this->renderDashboard(
            'dashboard/users/list', //subview 
            [
                'title' => 'List Users Page',
                'users' => $allUsers,
                'currentPage' => $currentPage,
                'totalPages' => $totalPages
            ]
        );
    }
    /**
     * Renders the create user subview on the dashboard view
     *     
     * @return void
     */
    public function createUser(): void
    {
        $roles = ['admin', 'editor', 'author'];
        $this->renderDashboard(
            'dashboard/users/create',
            [
                'title' => 'Create User Page',
                'roles' => $roles,
            ]
        );
    }
    /**
     * Stores new users
     *     
     * @return void
     */
    public function storeUser(): void //store new user
    {
        //check if current user is an admin
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('Access denied');
        }
        header('Content-Type: application/json');
        //sanitization
        $username = strip_tags(trim($_POST['username'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $repPassword =  $_POST['repeatedPassword'];
        $role = $_POST['role'];

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
                v::regex('/[\W]/')->setName('special char') //non-word char
            );
            if (!$regexRules->validate($password)) {
                $errors[] = 'Password must be at least 6 characters and include at least one uppercase letter, one lowercase letter, one number, and one special character.';
            }
        }
        //validate role
        $existingRoles = ['admin', 'editor', 'author'];
        if (!in_array($role, $existingRoles, true)) {
            $errors[] = 'Invalid role selected.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['error' => $errors]);
            return;
        }
        //check for duplicate credentials
        if ($this->userModel->getUser($email, $username)) {
            http_response_code(409); //
            echo json_encode(['error' => 'Another User exists with the same credentials. Choose another username or email.']);
            return;
        }

        $this->userModel->createUser($username, $email, $password, $role);

        echo json_encode(['success' => true]);
    }
    /**
     * Renders the edit user subview on the dashboard view
     *     
     * @param int $id  The id of the user to edit
     * 
     * @return void
     */
    public function editUser(int $id): void
    {
        $user = $this->userModel->getUserById($id);
        $roles = ['admin', 'editor', 'author'];

        $this->renderDashboard(
            'dashboard/users/edit', //subview 
            [
                'title' => 'Edit User Page',
                'user' => $user,
                'roles' => $roles,
            ]
        );
    }
    /**
     * Updates a user
     *     
     * @param int $id  The id of the user to update
     * 
     * @return void
     */
    public function updateUser(int $id): void
    {
        // check if current user is an admin
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('Access denied');
        }

        header('Content-Type: application/json');
        if ($_POST['method'] !== 'UPDATE') {
            http_response_code(405);
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }
        //check if current admin id matches the id to update
        if ($id === ($_SESSION['user']['id'] ?? null)) {
            http_response_code(403); // admin
            echo json_encode(['error' => "Admin can only update other users. Admin's own account can be updated in Profile section"]);
            return;
        }
        //check if user exists by the given id
        $userToEdit = $this->userModel->getUserById($id);
        if (!$userToEdit) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            exit();
        }
        //Sanitize input
        $username = strip_tags(trim($_POST['username'] ?? ''));
        $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
        $role = $_POST['role'] ?? '';

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

        //validate role
        $existingRoles = ['admin', 'editor', 'author'];
        if (!in_array($role, $existingRoles, true)) {
            $errors[] = 'Invalid role selected.';
        }

        //no changes
        if ($userToEdit['username'] === $username && $userToEdit['email'] === $email && $userToEdit['role'] === $role) {
            $errors[] = 'No changes were made: Choose another Username, Email or Role.';
        }

        if ($errors) {
            http_response_code(422);
            echo json_encode(['error' => $errors]);
            return;
        }
        //check for duplicate credentials
        $existingUser = $this->userModel->getUser($email, $username);
        if ($existingUser && $existingUser['id'] !== $id) {
            http_response_code(409); //
            echo json_encode(['error' => 'Another User exists with the same credentials. Choose another username or email.']);
            return;
        }
        $this->userModel->updateUser($id, $username, $email, null, $role);
        echo json_encode(['success' => true]);
    }
    /**
     * Deletes a user
     *     
     * @param int $id  The id of the user to delete
     * 
     * @return void
     */
    public function deleteUser(int $id): void
    {
        //check if current user is an admin
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('Access denied');
        }
        header('Content-Type: application/json');
        if ($_POST['method'] !== 'DELETE') {
            http_response_code(405);
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }
        //check if current user's id matches the user to delete id
        if (($_SESSION['user']['role'] ?? '') === 'admin' &&  ($_SESSION['user']['id'] ?? null)  === $id) {
            http_response_code(403);
            echo json_encode(['error' => 'Admins cannot delete their own accounts']);
            return;
        }
        //check if user exists
        if (!$this->userModel->getUserById($id)) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found']);
            return;
        }
        $this->userModel->deleteUser($id);
        echo json_encode(['success' => true]);
    }
}
