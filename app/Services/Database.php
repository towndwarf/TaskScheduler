<?php

namespace App\Services;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            try {
                $host = 'localhost';
                $dbname = 'ilife-cell_DB_name';
                $username = 'ilife_DB';
                $password = 'Arv%2q935ShimM';

                self::$connection = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('DB Connection Failed: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}