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

namespace pjanczyk\lo1olkusz;

class Router
{
    private static $controllerName;
    private static $action;
    private static $params;

    public static function route($map, $errorCallback)
    {
        if (self::parsePath($map)) {
            $controller = new self::$controllerName;

            $action = $_SERVER['REQUEST_METHOD'] . '_' . str_replace('-', '_', self::$action) . '_' . count(self::$params);

            if (method_exists($controller, $action)) {
                call_user_func_array([$controller, $action], self::$params);
            }
            else {
                $errorCallback();
            }
        }
        else {
            $errorCallback();
        }
    }

    private static function parsePath($map)
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
                    self::$controllerName = $map[''];
                    self::$action = '';
                    self::$params = [];

                    return true;
                }
            }
            else {
                self::$controllerName = $value;
                self::$action = isset($path[0]) ? $path[0] : '';
                self::$params = array_slice($path, 1);

                return true;
            }
        }
        return false;
    }
}