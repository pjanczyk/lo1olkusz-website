<?php

namespace pjanczyk\Framework;


class Router
{
    private $controllerName;
    private $action;
    private $params;

    public function __construct($map, $errorCallback)
    {
        if ($this->route($map)) {
            $controller = new $this->controllerName;

            if (method_exists($controller, $this->action)) {
                call_user_func_array([$controller, $this->action], $this->params);
            } else {
                $errorCallback();
            }
        }
        else {
            $errorCallback();
        }
    }

    private function route($map)
    {
        $path = isset($_GET['p']) ? $_GET['p'] : ''; //default path: ""
        $path = urldecode($path);
        $path = trim($path, '/');
        $path = filter_var($path, FILTER_SANITIZE_URL);
        $path = explode('/', $path);

        while (isset($path[0], $map[$path[0]])) {
            $value = $map[$path[0]];
            $path = array_slice($path, 1);

            if (is_array($value)) {
                $map = $value;

                if (count($path) == 0 && isset($map[''])) {
                    $this->controllerName = $map[''];
                    $this->action = 'index';
                    $this->params = [];

                    return true;
                }
            } else {
                $this->controllerName = $value;
                $this->action = isset($path[0]) ? str_replace('-', '_', $path[0]) : 'index';
                $this->params = array_slice($path, 1);

                return true;
            }
        }
        return false;
    }
}