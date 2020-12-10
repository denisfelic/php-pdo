<?php

namespace Alura\Pdo\Infrastructure\Persistence;

use PDO;

class CreateConnection
{

    public static function sqlite(): PDO
    {

        $dbPath = __DIR__ . '/../../../db.sqlite';
        return new PDO("sqlite:${dbPath}");
    }

    /**
     * @return PDO
     * @throws \PDOException
     */
    public static function mysql(): PDO
    {
        $connection = new PDO("mysql:host=localhost;port=3306;dbname=test", 'mysql', '', null);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $connection;
    }
}
