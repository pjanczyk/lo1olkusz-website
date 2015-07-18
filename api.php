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

//Created on 2015-07-10

require_once 'classes/Json.php';

use pjanczyk\lo1olkusz\Json;

//make sure path is specified and does not contain ".."
if (!isset($_GET['p']) || strpos($_GET['p'], '..') !== false) {
    header('HTTP/1.0 400 Bad Request');
    header('Content-Type: application/json');
    echo '{"error":"bad request"}';
    exit;
}

$args = explode('/', trim($_GET['p'], '/'));
if ($args[0] == 'timetables') {
    include 'api/timetables.php';
}
else {
    $path = $_ENV['OPENSHIFT_DATA_DIR'] . $_GET['p'];

    if (!file_exists($path)) {
        Json::notFound();
        exit;
    }

    header('Content-Type: application/json');
    readfile($path);
}