<?php

namespace pjanczyk\lo1olkusz\dashboard;


abstract class Controller {

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function index();
}