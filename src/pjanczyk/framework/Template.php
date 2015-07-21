<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-21
 * Time: 14:29
 */

namespace pjanczyk\framework;


class Template {

    protected $filename;
    protected $_data;

    public function __construct($filename) {
        $this->filename = $filename;
    }

    public function __get($name) {
        if(isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        return null;
    }

    public function __set($name, $value) {
        $this->_data[$name] = $value;
    }

    public function render() {
        extract($this->_data);
        include 'templates/' .  $this->filename . '.php';
    }
}