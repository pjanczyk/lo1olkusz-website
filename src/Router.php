<?php
/**
 * Copyright (C) 2016  Piotr Janczyk
 *
 * This file is part of lo1olkusz unofficial app - website.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace pjanczyk\lo1olkusz;

/**
 * Provides a simple routing.
 * Routes a request to the specific controller and calls its method
 * with signature "<REQUEST_METHOD>_<ACTION>_<NUMBER_OF_PARAMS>"
 * E.g. if controllerMap is set to ["users" => "UserController"]:
 *  "POST /users/edit/John" is mapped to UserController::POST_edit_1("John")
 *  "GET /users" is mapped to UserController::GET__0()
 */
class Router
{
    private $map;
    private $namespace;
    private $errorCallback;
    private $controllerName;
    private $action;
    private $params;

    /**
     * Creates a new instance of Router
     * @return Router
     */
    public static function newInstance()
    {
        return new self;
    }

    /**
     * @param callable $errorCallback
     * @return Router
     */
    public function setErrorCallback($errorCallback)
    {
        $this->errorCallback = $errorCallback;
        return $this;
    }

    /**
     * @param array $map
     * @return Router
     */
    public function setControllerMap($map)
    {
        $this->map = $map;
        return $this;
    }

    /**
     * @param string $namespace
     * @return Router
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
        return $this;
    }

    public function route($path)
    {
        if ($this->parsePath($path)) {
            $controllerName = $this->namespace . $this->controllerName;
            $controller = new $controllerName;

            $action = $_SERVER['REQUEST_METHOD'] . '_' . str_replace('-', '_', $this->action) . '_' . count($this->params);

            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], $this->params);
                return;
            }
        }

        $callback = $this->errorCallback;
        $callback();
    }

    private function parsePath($path)
    {
        $map = $this->map;

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
                    $this->action = '';
                    $this->params = [];

                    return true;
                }
            }
            else {
                $this->controllerName = $value;
                $this->action = isset($path[0]) ? $path[0] : '';
                $this->params = array_slice($path, 1);

                return true;
            }
        }
        return false;
    }
}