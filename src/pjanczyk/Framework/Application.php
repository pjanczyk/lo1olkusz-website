<?php
/**
 * Copyright 2015 Piotr Janczyk
 *
 * This file is part of I LO Olkusz Unofficial App.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace pjanczyk\Framework;


final class Application
{
    /** @var Application|null */
    private static $instance = null;

    /** @var Config */
    private $config;
    /** @var \PDO */
    private $db;
    private $controller;
    private $controllerName;
    private $action;
    private $params;


    /** @return Application */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /** @return \PDO */
    public static function getDb()
    {
        return self::$instance->db;
    }

    /** @return Config */
    public static function getConfig()
    {
        return self::$instance->config;
    }

    public function init(Config $config)
    {
        $this->config = $config;
        $this->connectToDb();
    }

    public function displayPage()
    {
        if (!$this->route($this->config->getRoute())) {
            http404();
        }

        $this->controller = new $this->controllerName;

        if (method_exists($this->controller, $this->action)) {
            call_user_func_array([$this->controller, $this->action], $this->params);
        } else {
            http404();
        }
    }

    private function connectToDb()
    {
        $this->db = new \PDO(
            $this->config->getDbDSN(),
            $this->config->getDbUser(),
            $this->config->getDbPassword(),
            $this->config->getDbOptions());
    }

    private function route($map)
    {
        $path = isset($_GET['p']) ? urldecode($_GET['p']) : ''; //default path: ""
        $path = trim($path, '/');
        $path = filter_var($path, FILTER_SANITIZE_URL);
        $path = explode('/', $path);

        while (isset($path[0], $map[$path[0]])) {
            $value = $map[$path[0]];

            $path = array_slice($path, 1);

            if (is_array($value)) {
                $map = $value;
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