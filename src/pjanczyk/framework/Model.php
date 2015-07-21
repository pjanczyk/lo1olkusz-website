<?php

namespace pjanczyk\framework;


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