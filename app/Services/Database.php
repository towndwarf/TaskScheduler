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
                $db_host = 'localhost';
                $db_name = 'ilife-cell_db_name';
                $db_username = 'ilife_DB';
                $db_password = 'Arv%2q935ShimM';

                self::$connection = new PDO(
                    "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4",
                    $db_username,
                    $db_password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $exception) {
                die('DB Connection Failed: ' . $exception->getMessage());
            }
        }

        return self::$connection;
    }
}