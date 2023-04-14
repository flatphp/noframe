<?php namespace Lib;

use PDO;
use PDOStatement;

/**
 * database connection and query tool
 */
class DbConn
{
    protected $pdo;
    
    public function __construct($config)
    {
        $options = $config['options'] ?? [];
        $options = array_diff_key([PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION], $options) + $options;
        $this->pdo = new PDO($config['dsn'], $config['username'], $config['password'], $options);
    }
    
    public function getPdo(): PDO
    {
        return $this->pdo;
    }


    /**
     * fetch all array with assoc, empty array returned if nothing or false
     * @param string $sql
     * @param mixed $bind
     * @return array
     */
    public function fetchAll(string $sql, $bind = null): array
    {
        return $this->selectPrepare($sql, $bind)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * fetch all with first field as indexed key, empty array returned if nothing or false
     * @param string $sql
     * @param mixed $bind
     * @return array
     */
    public function fetchAllIndexed(string $sql, $bind = null): array
    {
        return $this->selectPrepare($sql, $bind)->fetchAll(PDO::FETCH_UNIQUE | PDO::FETCH_ASSOC);
    }

    /**
     * fetch all grouped array with first field as keys, empty array returned if nothing or false
     * @param string $sql
     * @param mixed $bind
     * @return array
     */
    public function fetchAllGrouped(string $sql, $bind = null): array
    {
        return $this->selectPrepare($sql, $bind)->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_ASSOC);
    }

    /**
     * fetch one row array with assoc, empty array returned if nothing or false
     * @param string $sql
     * @param mixed $bind
     * @return array
     */
    public function fetchRow(string $sql, $bind = null): array
    {
        return $this->selectPrepare($sql, $bind)->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * fetch first column array, empty array returned if nothing or false
     * @param string $sql
     * @param mixed $bind
     * @return array
     */
    public function fetchColumn(string $sql, $bind = null): array
    {
        return $this->selectPrepare($sql, $bind)->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    /**
     * fetch pairs of first column as Key and second column as Value, empty array returned if nothing or false
     * @param string $sql
     * @param mixed $bind
     * @return array
     */
    public function fetchPairs(string $sql, $bind = null): array
    {
        return $this->selectPrepare($sql, $bind)->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    /**
     * fetch grouped pairs of K/V with first field as keys of grouped array, empty array returned if nothing of false
     * @param string $sql
     * @param mixed $bind
     * @return array
     */
    public function fetchPairsGrouped(string $sql, $bind = null): array
    {
        $data = [];
        foreach ($this->selectPrepare($sql, $bind)->fetchAll(PDO::FETCH_NUM) as $row) {
            $data[$row[0]] = [$row[1] => $row[2]];
        }
        return $data;
    }

    /**
     * fetch one column value, false returned if nothing or false
     * @param string $sql
     * @param mixed $bind
     * @return mixed
     */
    public function fetchOne(string $sql, $bind = null)
    {
        return $this->selectPrepare($sql, $bind)->fetchColumn();
    }


    /**
     * @param $sql
     * @param null $bind
     * @param null $fetch_mode
     * @return PDOStatement
     */
    public function selectPrepare($sql, $bind = null, $fetch_mode = null): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->bind($bind));
        if (null !== $fetch_mode) {
            $stmt->setFetchMode($fetch_mode);
        }
        return $stmt;
    }

    /**
     * Execute an SQL statement and return the boolean result.
     * @param string $sql
     * @param mixed $bind
     * @param int &$affected_rows
     * @return bool
     */
    public function execute(string $sql, $bind = null, int &$affected_rows = 0): bool
    {
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($this->bind($bind));
        $affected_rows = $stmt->rowCount();
        return $res;
    }

    /**
     * Get last insert id
     * @return false|string
     */
    public function getLastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    /**
     * Parse bind as array
     * @param mixed $bind
     * @return null|array
     */
    protected function bind($bind): ?array
    {
        if ($bind === null) {
            return null;
        }
        if (!is_array($bind)) {
            $bind = [$bind];
        }
        return array_values($bind);
    }
}