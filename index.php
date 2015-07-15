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
    '' => [
        'title' => 'Cron logs',
        'include' => 'cron_log.php'
    ],
    'ln' => [
        'title' => 'Lucky numbers',
        'include' => 'list.php'
    ],
    'replacements' => [
        'title' => 'Replacements',
        'include' => 'list.php'
    ],
    'timetable' => [
        'title' => 'Timetables',
        'include' => 'list.php'
    ]
];

$currentPage = '';

if (isset($_GET['p']) && isset($pages[$_GET['p']])) {
    $currentPage = $_GET['p'];
}

include 'html/header.php';

echo '<h3>' . $pages[$currentPage]['title'] . '</h3>';

include 'pages/' . $pages[$currentPage]['include'];

include 'html/footer.php';