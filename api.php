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

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL|E_STRICT);

require 'autoloader.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Models\LuckyNumbersModel;
use pjanczyk\lo1olkusz\Models\ReplacementsModel;
use pjanczyk\lo1olkusz\Models\TimetablesModel;

if (!isset($_GET['p'])) {
    Json::badRequest();
    exit;
}

$args = explode('/', trim($_GET['p'], '/'));

if ($args[0] == 'ln') {
    $model = new LuckyNumbersModel(Database::connect());

    if (count($args) == 2) { # /api/ln/<date>
        $ln = $model->get($args[1]);
        if ($ln !== null) {
            Json::OK($ln);
        }
        else {
            Json::notFound();
        }
    }
    else {
        Json::badRequest();
    }
}
else if ($args[0] == 'replacements') {
    $model = new ReplacementsModel(Database::connect());

    if (count($args) == 3) { # /api/replacements/<date>/<class>
        $replacements = $model->get($args[2], $args[1]);
        if ($replacements !== false) {
            Json::OK($replacements);
        }
        else {
            Json::notFound();
        }
    }
    else {
        Json::badRequest();
    }
}
else if ($args[0] = 'timetables') {
    $model = new TimetablesModel(Database::connect());

    if (count($args) == 1) { # /api/timetables
        $timetables = $model->getAll([TimetablesModel::FIELD_CLASS]);
        Json::OK($timetables);
    }
    else if (count($args) == 2) { # /api/timetables/<class>
        $timetable = $model->get($args[1]);
        if ($timetable !== false) {
            Json::OK($timetable);
        }
        else {
            Json::notFound();
        }
    }
    else {
        Json::badRequest();
    }
}
else {
    Json::badRequest();
}

//if ($args[0] == 'timetables') {
//    include 'api/timetables.php';
//}
//else {
//    $path = $_ENV['OPENSHIFT_DATA_DIR'] . $_GET['p'];
//
//    if (!file_exists($path)) {
//        Json::notFound();
//        exit;
//    }
//
//    header('Content-Type: application/json');
//    readfile($path);
//}