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

$pages = [
    'cron' => [
        'title' => 'Cron',
        'include' => 'cron.php'
    ],
    'ln' => [
        'title' => 'Lucky numbers',
        'include' => 'ln.php'
    ],
    'replacements' => [
        'title' => 'Replacements',
        'include' => 'replacements.php'
    ],
    'settings' => [
        'title' => 'Settings',
        'include' => 'settings.php'
    ],
    'timetable' => [
        'title' => 'Timetables',
        'include' => 'timetable.php'
    ]
];

$get = isset($_GET['p']) ? $_GET['p'] : '';
$args = explode('/', $get);
$currentPage = $args[0];
$args = array_slice($args, 1);

if (isset($pages[$currentPage])) {
    include 'controllers/' . $pages[$currentPage]['include'];
}
else {
    include 'controllers/main.php';
}


