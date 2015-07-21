<?php

namespace pjanczyk\MVC;


use pjanczyk\sql\Database;

class Model {

    /** @var Database */
    protected $db;

    /**
     * @param Database $db
     */
    public function __construct($db) {
        $this->db = $db;
    }
}