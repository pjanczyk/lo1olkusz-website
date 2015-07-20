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

//Created on 2015-07-09

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL|E_STRICT);

require_once 'classes/Database.php';

use pjanczyk\lo1olkusz\Database;

$menu = [
    [
        'title' => 'Replacements',
        'class' => 'pjanczyk\lo1olkusz\Controllers\Replacements',
        'include' => 'controllers/Replacements.php'
    ],
    [
        'title' => 'Lucky numbers',
        'include' => 'ln.php'
    ],
    [
        'title' => 'Replacements',
        'include' => 'replacements.php'
    ],
    [
        'title' => 'Settings',
        'include' => 'settings.php'
    ],
    [
        'title' => 'Timetables',
        'include' => 'timetable.php'
    ]
];

$controllersNamespace = 'pjanczyk\lo1olkusz\Dashboard\\';


function start() {
    date_default_timezone_set('Europe/Warsaw');
    $db = Database::connect();

    $url = isset($_GET['p']) ? $_GET['p'] : '';
    $url = trim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $controllerName = isset($url[0]) ? $url[0] : 'index';
    $controllerClass = 'pjanczyk\lo1olkusz\Dashboard\\' . $controllerName;
    $action = isset($url[1]) ? $url[1] : 'index';
    unset($url[0], $url[1]);
    $params = array_values($url);

    $controllerPath = 'controllers/' . $controllerName . '.php';
    if (file_exists($controllerPath)) {
        require $controllerPath;
        $controller = new $controllerClass($db);
        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], $params);
        }
        else {
            header('HTTP/1.0 404 Not Found');
            include 'html/404.html';
        }
    }
    else {
        header('HTTP/1.0 404 Not Found');
        include 'html/404.html';
    }
}

start();