<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-20
 * Time: 17:19
 */

namespace pjanczyk\framework\internal;

abstract class SqlBuilder {

    /** @var \PDO */
    protected $pdo;
    protected $binds;

    /**
     * @param \PDO $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * @return \PDOStatement
     */
    public function prepare() {
        $sql = $this->buildSql();
        return $this->pdo->prepare($sql);
    }

    /**
     * @return string
     */
    abstract public function buildSql();
}