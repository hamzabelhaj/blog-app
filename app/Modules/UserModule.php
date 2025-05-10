<?php

namespace App\Modules;

use App\Services\DB;

class UserModule
{
    protected DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }

    public function findByEmail(string $email): ?array
    {
        return $this->db->find("SELECT * FROM users WHERE email = :email", ['email' => $email]);
    }

    public function createUser(string $username, string $email, string $password): bool
    {
        return $this->db->insert("INSERT INTO users (username, email, password) VALUES (:u, :e, :p)", [
            'u' => $username,
            'e' => $email,
            'p' => password_hash($password, PASSWORD_DEFAULT),
        ]);
    }

    public function exists(string $email): bool
    {
        return $this->db->find("SELECT id FROM users WHERE email = :email", ['email' => $email]) !== null;
    }
}
