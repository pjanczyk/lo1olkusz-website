<?php

namespace pjanczyk\framework;


abstract class Controller {

    /** @var Database */
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function index();

    protected function includeView($name) {
        return new View($name);
    }
}