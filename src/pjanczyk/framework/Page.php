<?php

namespace pjanczyk\framework;


abstract class Page {

    /** @var Database */
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function index();

    protected function includeTemplate($name) {
        return new Template($name);
    }
}