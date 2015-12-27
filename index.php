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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

require 'autoloader.php';
require 'config.php';

use pjanczyk\lo1olkusz\Auth;
use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Router;
use pjanczyk\lo1olkusz\Config;

date_default_timezone_set('Europe/Warsaw');

session_start();
Database::init(Config::getInstance()->getDatabaseConfig());
Auth::init();

$route = [
    '' => 'pjanczyk\lo1olkusz\Controller\HomeController',
    'contact' => 'pjanczyk\lo1olkusz\Controller\ContactController',
    'download' => 'pjanczyk\lo1olkusz\Controller\DownloadController',
    'dashboard' => [
        '' => 'pjanczyk\lo1olkusz\Controller\Dashboard\HomeController',
        'login' => 'pjanczyk\lo1olkusz\Controller\Dashboard\LoginController',
        'replacements' => 'pjanczyk\lo1olkusz\Controller\Dashboard\ReplacementController',
        'lucky-numbers' => 'pjanczyk\lo1olkusz\Controller\Dashboard\LuckyNumberController',
        'settings' => 'pjanczyk\lo1olkusz\Controller\Dashboard\SettingController',
        'timetables' => 'pjanczyk\lo1olkusz\Controller\Dashboard\TimetableController',
        'cron' => 'pjanczyk\lo1olkusz\Controller\Dashboard\CronController'
    ],
    'api' => 'pjanczyk\lo1olkusz\Controller\ApiController'
];

Router::route($route, function () {
    header('HTTP/1.0 404 Not Found');
    include '404.html';
    exit;
});