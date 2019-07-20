<?php

namespace App;

use PDO;

/**
 * Database helper Class
 * @package App
 */
class Database
{
    /**
     * @var PDO Database connection
     */
    private $pdo;

    /**
     * Database constructor.
     */
    public function __construct()
    {
        $this->pdo = new PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']};",
            $_ENV['DB_USER'],
            $_ENV['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]
        );
    }

    /**
     * Builds, executes and returns PDO statement
     * @param string $sql
     * @param array $params Optional params to bind to query
     * @return \PDOStatement
     */
    protected function query(string $sql, array $params = []): \PDOStatement
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        return $query;
    }

    /**
     * Builds and executes SQL statement
     * @param string $sql
     * @param array $params Optional params to bind to statement
     * @return bool|int
     */
    protected function exec(string $sql, array $params = [])
    {
        $query = $this->pdo->prepare($sql);

        if (!$query->execute($params)) {
            return false;
        }

        return $query->rowCount();
    }

    /**
     * Queries database and returns array of results
     * @param string $sql
     * @param array $params Optional params to bind to query
     * @return array
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        $pdo = new self();
        $query = $pdo->query($sql, $params);
        return $query->fetchAll();
    }

    /**
     * Executes SQL statement and returns number of rows affected
     * @param string $sql
     * @param array $params
     * @return bool|int
     */
    public static function execute(string $sql, array $params = [])
    {
        $pdo = new self();
        return $pdo->exec($sql, $params);
    }
}
