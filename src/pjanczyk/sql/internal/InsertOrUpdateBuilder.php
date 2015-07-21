<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-20
 * Time: 17:44
 */

namespace pjanczyk\sql\internal;


class InsertOrUpdateBuilder extends SqlBuilder {

    private $table;
    private $indexes = null;
    private $set = null;

    /**
     * @param \PDO $pdo
     * @param string $table
     */
    public function __construct($pdo, $table) {
        parent::__construct($pdo);

        $this->table = $table;
    }

    /**
     * @param array $array
     * @return $this
     */
    public function where($array) {
        $this->indexes = $array;
        return $this;
    }

    /**
     * @param array $array
     * @return $this
     */
    public function set($array) {
        $this->set = $array;
        return $this;
    }

    public function buildSql() {
        if (empty($this->indexes) || empty($this->set)) {
            throw new \BadFunctionCallException('where() and set() must be called before buildSql()');
        }

        $all = $this->indexes + $this->set;

        $columns = implode(',', array_keys($all));
        $values = implode(',', array_values($all));

        array_walk($this->set, function(&$v, $k) { $v = "$k=$v"; });
        $set = implode(',', $this->set);

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$values}) ON DUPLICATE KEY UPDATE {$set}";
        return $sql;
    }
}