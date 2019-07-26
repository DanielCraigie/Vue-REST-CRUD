<?php

namespace App;

use PDO;
use PDOStatement;
use Exception;

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
     * @return PDOStatement
     * @throws Exception
     */
    protected function query(string $sql, array $params = []): PDOStatement
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        if (!empty($query->errorCode())
            && $query->errorCode() != '00000'
        ) {
            throw new Exception('DB Error: ' . $query->errorCode() . ' - ' . $query->errorInfo()[0]);
        }

        return $query;
    }

    /**
     * Builds and executes SQL statement
     * @param string $sql
     * @param array $params Optional params to bind to statement
     * @return PDOStatement
     * @throws Exception
     */
    protected function exec(string $sql, array $params = []): PDOStatement
    {
        $query = $this->pdo->prepare($sql);
        $query->execute($params);

        if (!empty($query->errorCode())
            && $query->errorCode() != '00000'
        ) {
            throw new Exception('DB Error: ' . $query->errorCode() . ' - ' . $query->errorInfo()[0]);
        }

        return $query;
    }

    /**
     * @return string
     */
    protected function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Queries database and returns array of results
     * @param string $sql
     * @param array $params Optional params to bind to query
     * @return array
     * @throws Exception
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        $pdo = new self();
        return $pdo->query($sql, $params)->fetchAll();
    }

    /**
     * Executes SQL statement and returns number of rows affected
     * @param string $sql
     * @param array $params
     * @return int
     * @throws Exception
     */
    public static function execute(string $sql, array $params = []): int
    {
        $pdo = new self();
        return $pdo->exec($sql, $params)->rowCount();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return int
     * @throws Exception
     */
    public static function insert(string $sql, array $params): int
    {
        $pdo = new self();
        $pdo->exec($sql, $params);
        return $pdo->lastInsertId();
    }

    /**
     * @param string $sql
     * @param array $params
     * @return int
     * @throws Exception
     */
    public static function update(string $sql, array $params): int
    {
        $pdo = new self();
        return $pdo->exec($sql, $params)->rowCount();
    }
}
