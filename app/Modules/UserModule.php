<?php

namespace App\Modules;

use App\Services\DatabaseHandler;

class UserModule
{
    protected DatabaseHandler $databaseHandler;

    public function __construct()
    {
        $this->databaseHandler = new DatabaseHandler();
    }

    public function getUserByEmail(string $email): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
        $params = ['email' => $email];
        $records = $this->databaseHandler->select($query, $params);
        return $records[0] ?? null;
    }
    
     public function getUserByEmailOrUsername(string $email, ?string $username = null): ?array
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $params = ['email' => $email];

        if (!empty($username)) {
            $query .= " OR username = :username";
            $params['username'] = $username;
        }

        $query .= " LIMIT 1";
        $records = $this->databaseHandler->select($query, $params);
        return $records[0] ?? null;
    }

    public function createUser(string $username, string $email, string $password): bool
    {
        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $params = [
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password
        ];
        return $this->databaseHandler->insert($query, $params);
    }

    public function exists(string $email, ?string $username = null): bool
    {
        return $this->getUserByEmailOrUsername($email, $username) !== null;
    }
}
