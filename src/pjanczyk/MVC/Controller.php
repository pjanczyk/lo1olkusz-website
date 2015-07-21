<?php

namespace pjanczyk\MVC;


abstract class Controller {

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function index();

    protected function includeTemplate($name) {
        global $menu;
        global $key;
        include 'templates/' . $name . '.php';
    }
}