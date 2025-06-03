<?php

/**
 * DatabaseHandler class
 * 
 * Provides reusable methods for executing database operations such as 
 * SELECT, INSERT, UPDATE, and DELETE using prepared statements.
 */

declare(strict_types=1);

namespace App\Services;

use PDO;
use PDOException;
use App\Services\Database;

class DatabaseHandler extends Database
{
    protected PDO $pdo;

    public function __construct()
    {
        $this->pdo = $this->connection();
    }
    /**
     * Executes a SELECT query and returns the fetched results.
     *
     * @param string $query   The SQL SELECT statement
     * @param array|null $params Optional parameters for the prepared statement
     * @return array Result set as an associative array, or empty array on failure
     */
    public function select(string $query, ?array $params = []): array
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $records;
        } catch (PDOException $e) {
            error_log("Select Error: " . $e->getMessage());
            return [];
        }
    }
    /**
     * Executes an INSERT query.
     *
     * @param string $query   The SQL INSERT statement
     * @param array $params   Parameters for the prepared statement
     * @return bool Success status
     */
    public function insert(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Insert Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Executes an UPDATE query.
     *
     * @param string $query   The SQL UPDATE statement
     * @param array $params   Parameters for the prepared statement
     * @return bool Success status
     */
    public function update(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Update Error: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Executes a DELETE query.
     *
     * @param string $query   The SQL DELETE statement
     * @param array $params   Parameters for the prepared statement
     * @return bool Success status
     */
    public function delete(string $query, array $params = []): bool
    {
        try {
            $stmt = $this->pdo->prepare($query);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Delete Error: " . $e->getMessage());
            return false;
        }
    }
}
