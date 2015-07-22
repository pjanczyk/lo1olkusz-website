<?php

namespace pjanczyk\framework;


use PDO;

class Database {

    private $pdo;

    /**
     * Opens connection to the database
     * @param Config $config
     */
    public function __construct(Config $config) {
        $this->pdo = new PDO($config->getDbDSN(), $config->getDbDSN(), $config->getDbUser(), $config->getDbPassword(), $config->getDbOptions());
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