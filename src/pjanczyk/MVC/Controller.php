<?php

namespace pjanczyk\MVC;


abstract class Controller {

    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    abstract public function index();

    protected function includeTemplate($name) {
        global $key;

        $template = new Template($name);
        $template->key = $key;
        return $template;
    }
}