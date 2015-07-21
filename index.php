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

require 'autoloader.php';

use pjanczyk\framework\Database;


function start() {
    $map = [
        'replacements' => 'pjanczyk\lo1olkusz\Dashboard\Pages\ReplacementsController',
        'lucky-numbers' => 'pjanczyk\lo1olkusz\Dashboard\Pages\LuckyNumbersController',
        'settings' => 'pjanczyk\lo1olkusz\Dashboard\Pages\SettingsController',
        'cron' => 'pjanczyk\lo1olkusz\Dashboard\Pages\CronController',
        'default' => 'pjanczyk\lo1olkusz\Dashboard\Pages\DefaultController'
    ];

    date_default_timezone_set('Europe/Warsaw');
    $db = new Database;

    $url = isset($_GET['p']) ? $_GET['p'] : '';
    $url = trim($url, '/');
    $url = filter_var($url, FILTER_SANITIZE_URL);
    $url = explode('/', $url);

    global $key;

    if (isset($url[0], $map[$url[0]])) {
        $key = $url[0];
    }
    else {
        $key = 'default';
    }

    $controllerClass = $map[$key];

    $action = isset($url[1]) ? $url[1] : 'index';
    $action = str_replace('-', '_', $action);
    unset($url[0], $url[1]);
    $params = array_values($url);

    $controller = new $controllerClass($db);

    if (method_exists($controller, $action)) {
        call_user_func_array([$controller, $action], $params);
    }
    else {
        header('HTTP/1.0 404 Not Found');
        include 'html/404.html';
    }
}

start();