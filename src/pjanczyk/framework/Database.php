<?php

namespace pjanczyk\framework;


use PDO;
use pjanczyk\lo1olkusz\Config;

class Database {

    private $pdo;

    /**
     * Opens connection to the database
     */
    public function __construct() {
        $this->pdo = new PDO(Config::getDbDSN(), Config::getDbUser(), Config::getDbPassword(), Config::getDbOptions());
    }

    /**
     * @param string $table
     * @param array $columns
     * @return \pjanczyk\framework\internal\SelectBuilder
     */
    public function select($table, $columns) {
        return new internal\SelectBuilder($this->pdo, $table, $columns);
    }

    /**
     * @param string $table
     * @return internal\InsertOrUpdateBuilder
     */
    public function insertOrUpdate($table) {
        return new internal\InsertOrUpdateBuilder($this->pdo, $table);
    }

    /**
     * @param string $table
     * @return internal\DeleteBuilder
     */
    public function delete($table) {
        return new internal\DeleteBuilder($this->pdo, $table);
    }
}