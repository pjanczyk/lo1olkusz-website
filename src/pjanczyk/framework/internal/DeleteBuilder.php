<?php

namespace pjanczyk\framework\internal;


class DeleteBuilder extends SqlBuilder {

    private $table;
    private $where = null;

    /**
     * @param \PDO $pdo
     * @param string $table
     */
    public function __construct($pdo, $table) {
        parent::__construct($pdo);

        $this->table = $table;
    }

    /**
     * @param string|array $where
     * @return $this
     */
    public function where($where) {
        if (is_array($where)) {
            array_walk($where, function(&$v, $k) { $v = "$k=$v"; });
            $this->where = implode(' AND ', $where);
        }
        else {
            $this->where = $where;
        }
        return $this;
    }

    public function buildSql() {
        $sql = 'DELETE FROM '.$this->table;
        if ($this->where !== null) {
            $sql .= ' WHERE ' . $this->where;
        }
        return $sql;
    }
}