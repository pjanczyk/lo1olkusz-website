<?php

namespace pjanczyk\sql\internal;

class SelectBuilder extends SqlBuilder {

    private $table;
    private $columns;
    private $where = null;
    private $orderBy = [];

    /**
     * @param \PDO $pdo
     * @param string $table
     * @param array $columns
     */
    public function __construct($pdo, $table, $columns) {
        parent::__construct($pdo);

        if (count($columns) == 0) {
            new \InvalidArgumentException('$columns cannot be an empty array');
        }

        $this->table = $table;
        $this->columns = $columns;
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

    /**
     * @param string $column
     * @return $this
     */
    public function orderDesc($column) {
        $this->orderBy[] = [$column, true];
        return $this;
    }

    /**
     * @param string $column
     * @return $this
     */
    public function orderAsc($column) {
        $this->orderBy[] = [$column, false];
        return $this;
    }

    public function buildSql() {
        $columns = implode(',', $this->columns);
        $sql = 'SELECT '.$columns.' FROM '.$this->table;
        if ($this->where !== null) {
            $sql .= ' WHERE ' . $this->where;
        }
        if (count($this->orderBy) !== 0) {
            $sql .= ' ORDER BY ';
            $sql .= implode(',', array_map(function($o) {
                    return $o[0] . ($o[1] ? ' DESC' : ' ASC');
                }, $this->orderBy)
            );
        }
        return $sql;
    }
}
