<?php

namespace pjanczyk\MVC;


abstract class Controller {

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function index();
}