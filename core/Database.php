<?php

declare(strict_types=1);

namespace Core;

use PDO;
use PDOException;

class Database
{

    private static ?PDO $pdo = null;

    public static function connection(): PDO
    {
        if (self::$pdo === null) {

            try {
                self::$pdo = new PDO((DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET), DB_USER , DB_PASS);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Database connection failed: ' . $e->getMessage());
            }
        }

        return self::$pdo;
    }
}
