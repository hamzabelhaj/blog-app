<?php

/**
 * UserModel class
 * 
 * Handles database operations related to users such as retrieval, creation, updating, and deletion.
 */

declare(strict_types=1);

namespace App\Models;

use App\Services\DatabaseHandler;

class UserModel
{
    private DatabaseHandler $databaseHandler;

    public function __construct()
    {
        $this->databaseHandler = new DatabaseHandler();
    }

    /**
     * Retrieves all users with pagination.
     *
     * @param int $limit  Pagination limit
     * @param int $offset Pagination offset
     * @return array|null List of users
     */
    public function getAllUsers(int $limit, int $offset,): ?array
    {
        if (!is_int($limit) || !is_int($offset)) {
            throw new \InvalidArgumentException("Limit and offset must be integers.");
        }
        $query = "SELECT * FROM users ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset} ";
        $records = $this->databaseHandler->select($query);
        return $records ?? null;
    }
    /**
     * Retrieves users excluding the user with the given ID.
     *
     * @param int $id      ID to exclude
     * @param int $limit   Pagination limit
     * @param int $offset  Pagination offset
     * @return array|null  List of users
     */
    public function getNonCurrentUsers(int $id, int $limit, int $offset): ?array
    {
        if (!is_int($limit) || !is_int($offset)) {
            throw new \InvalidArgumentException("Limit and offset must be integers.");
        }
        $query = "SELECT * FROM users WHERE id != :id ORDER BY created_at DESC LIMIT {$limit} OFFSET {$offset} ";
        $params = [
            'id' => $id,
        ];
        $records = $this->databaseHandler->select($query, $params);
        return $records ?? null;
    }
    /**
     * Counts users with optional conditions.
     *
     * @param array|null $conditions Optional filtering conditions
     * @return int|null Total count of users
     */
    public function countUsers(?array $conditions = []): ?int
    {
        $query = "SELECT COUNT(*) AS total FROM users";
        $params = [];
        $allowedColumns = ['id', 'username', 'email', 'role'];
        $allowedOperators = ['=', '!=', '<', '>', '<=', '>='];
        if (!empty($conditions)) {
            $i = 0;
            foreach ($conditions as $cond) {
                $column = $cond['column'] ?? '';
                $operator = $cond['operator'] ?? '=';
                $value = $cond['value'] ?? null;
                if (!in_array($column, $allowedColumns, true)) { //to prevent sql injection
                    throw new \InvalidArgumentException("Invalid column: $column");
                }
                if (!in_array($operator, $allowedOperators, true)) {
                    throw new \InvalidArgumentException("Invalid operator: $operator");
                }
                $query .= ($i === 0 ? " WHERE" : " AND") . " $column $operator ?";
                $params[] = $value;
                $i++;
            }
        }
        $records = $this->databaseHandler->select($query, $params);
        return (int) $records[0]['total'] ?? null;
    }
    /**
     * Retrieves a user by email.
     *
     * @param string $email Email to search
     * @return array|null   User record
     */
    public function getUserByEmail(string $email): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $params = ['email' => $email];
        $records = $this->databaseHandler->select($query, $params);
        return $records[0] ?? null;
    }
    /**
     * Retrieves a user by ID.
     *
     * @param int $id User ID
     * @return array|null User record
     */
    public function getUserById(int $id): ?array
    {
        $query = "SELECT * FROM users WHERE id = :id LIMIT 1";
        $params = ['id' => $id];
        $records = $this->databaseHandler->select($query, $params);
        return $records[0] ?? null;
    }

    /**
     * Retrieves a user by email or username if also specified.
     *
     * @param string      $email    Email to search
     * @param string|null $username Optional username
     * @return array|null User data
     */
    public function getUser(string $email, ?string $username = null): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $params = ['email' => $email];

        if ($username !== null) {
            $query .= " OR username = :username";
            $params['username'] = $username;
        }

        $query .= " LIMIT 1";
        $records = $this->databaseHandler->select($query, $params);
        return $records[0] ?? null;
    }

    /**
     * Creates a new user with optional role incase a user creation was done by admin.
     *
     * @param string      $username
     * @param string      $email
     * @param string      $password
     * @param string|null $role     Optional role (for admin creation)
     * @return bool       Success status
     */
    public function createUser(string $username, string $email, string $password, ?string $role = null): bool
    {
        $query = "INSERT INTO users (username, email, password";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $params = [
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password
        ];
        if (!empty($role)) { //incase admin creates a new user
            $query .= ", role) VALUES (:username, :email, :password, :role)";
            $params['role'] = $role;
        } else {
            $query .= ") VALUES (:username, :email, :password)";
        }
        return $this->databaseHandler->insert($query, $params) ?? false;
    }


    /**
     * Updates a user's information.
     *
     * @param int         $id
     * @param string      $username
     * @param string      $email
     * @param string|null $password Optional password if should be updated
     * @param string|null $role     Optional role if should be updated
     * @return bool       Success status
     */
    public function updateUser(int $id, string $username, string $email, ?string $password = null, ?string $role = null): bool
    {
        $query = "UPDATE users SET username=:username, email=:email";
        $params = [
            'id' => $id,
            'username' => $username,
            'email' => $email,
        ];
        if (!empty($password)) { //incase current user updates his pwd
            $query .= ", password=:password";
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $params['password'] = $hashed_password;
        }

        if (!empty($role)) { //incase admin updates other users
            $query .= ", role=:role";
            $params['role'] = $role;
        }

        $query .= " WHERE id=:id";
        return $this->databaseHandler->update($query, $params) ?? false;
    }

    /**
     * Deletes a user by ID.
     *
     * @param int $id
     * @return bool Success status
     */
    public function deleteUser(int $id): bool
    {
        $query = "DELETE FROM users WHERE id = :id";
        $params = ['id' => $id];
        return $this->databaseHandler->delete($query, $params) ?? false;
    }
}
