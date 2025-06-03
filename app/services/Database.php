<?php

/**
 * Database connection handler
 * 
 * Provides a singleton PDO connection instance for use across the application.
 */

declare(strict_types=1);

namespace App\Services;

use PDO;
use PDOException;

class Database
{

    private ?PDO $pdo = null;
    /**
     * Returns a PDO database connection.
     * If one does not exist, it creates and configures it.
     *
     * @return PDO
     */
    protected function connection(): PDO
    {
        if ($this->pdo === null) {

            try {
                $this->pdo = new PDO((DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET), DB_USER, DB_PASS);
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }
        return $this->pdo;
    }
}
