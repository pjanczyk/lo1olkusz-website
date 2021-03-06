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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

require 'autoloader.php';

use pjanczyk\lo1olkusz\Auth;
use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Router;
use pjanczyk\lo1olkusz\Config;

date_default_timezone_set('Europe/Warsaw');

session_start();
Database::init(Config::getInstance()->getDatabaseConfig());
Auth::$disableSSL = true;
Auth::init();

$route = [
    '' => 'HomeController',
    'contact' => 'ContactController',
    'download' => 'DownloadController',
    'dashboard' => [
        '' => 'Dashboard\HomeController',
        'login' => 'Dashboard\LoginController',
        'replacements' => 'Dashboard\ReplacementController',
        'lucky-numbers' => 'Dashboard\LuckyNumberController',
        'settings' => 'Dashboard\SettingController',
        'timetables' => 'Dashboard\TimetableController',
        'cron' => 'Dashboard\CronController'
    ],
    'api' => 'ApiController'
];

$path = isset($_GET['p']) ? $_GET['p'] : '';

Router::newInstance()
    ->setNamespace('pjanczyk\\lo1olkusz\\Controller\\')
    ->setControllerMap($route)
    ->setErrorCallback(function () {
        header('HTTP/1.0 404 Not Found');
        include '404.html';
        exit;
    })
    ->route($path);