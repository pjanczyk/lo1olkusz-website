<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-19
 * Time: 21:17
 */

namespace pjanczyk\sql\internal;

class SelectBuilder {

    private $table;
    private $columns;
    private $where = null;
    private $orderBy = [];

    public function __construct($table, $columns) {
        if (count($columns) == 0) {
            new \InvalidArgumentException('$columns cannot be an empty array');
        }

        $this->table = $table;
        $this->columns = $columns;
    }

    /**
     * @param string $where
     * @return SelectBuilder
     */
    public function where($where) {
        $this->where = $where;
        return $this;
    }

    /**
     * @param string $column
     * @return SelectBuilder
     */
    public function orderDesc($column) {
        $this->orderBy[] = [$column, true];
        return $this;
    }

    /**
     * @param string $column
     * @return SelectBuilder
     */
    public function orderAsc($column) {
        $this->orderBy[] = [$column, false];
        return $this;
    }

    /**
     * @return string
     */
    public function sql() {
        $columns = '`' . implode('`,`', $this->columns) . '`';
        $sql = "SELECT {$columns} FROM `{$this->table}`";
        if ($this->where !== null) {
            $sql .= ' WHERE ' . $this->where;
        }
        if (count($this->orderBy) !== 0) {
            $sql .= ' ORDER BY ';
            $sql .= implode(',', array_map(function($o) {
                    return '`' . $o[0] . '` ' . ($o[1] ? 'DESC' : 'ASC');
                }, $this->orderBy)
            );
        }
        return $sql;
    }
}


namespace pjanczyk\sql;

class SqlBuilder {

    /**
     * @param string $table
     * @param array $columns
     * @return internal\SelectBuilder
     */
    public static function select($table, $columns) {
        return new internal\SelectBuilder($table, $columns);
    }
}