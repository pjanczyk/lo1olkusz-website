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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL | E_STRICT);

require 'autoloader.php';

use pjanczyk\framework\Database;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Model\NewsModel;

$binary = isset($_GET['bin']);

/**
 * 1 byte
 * @param int $int
 */
function binUnsignedByte($int) {
    echo pack('C', (int)$int);
}

/**
 * 2 bytes
 * @param int $int
 */
function binUnsignedShort($int) {
    echo pack('n', (int)$int);
}

/**
 * 4 bytes
 * @param int $int
 */
function binUnsignedLong($int) {
    echo pack('N', (int)$int);
}

/**
 * string length + 2 bytes
 * @param string $string max length 65535
 */
function binString($string) {
    binUnsignedShort(strlen($string));
    echo $string;
}

/**
 * 4 bytes
 * @param string $date in format yyyy-mm-dd
 */
function binDate($date) {
    if (strlen($date) === 10) {
        echo pack('nCC', (int)substr($date, 0, 4), (int)substr($date, 5, 2), (int)substr($date, 8, 2));
    }
    else {
        echo pack('N', 0);
    }
}

function binReplacements($n) {
    binUnsignedLong($n['timestamp']);
    binString($n['class']);
    binDate($n['date']);
    $replacements = json_decode($n['value'], true);
    binUnsignedByte(count($replacements));
    foreach ($replacements as $h=>$v) {
        binUnsignedByte($h);
        binString($v);
    }
}

function binLuckyNumber($n) {
    binUnsignedLong($n['timestamp']);
    binDate($n['date']);
    binUnsignedByte($n['value']);
}

function binTimetable($n) {
    binUnsignedLong($n['timestamp']);
    binString($n['class']);
    binString($n['value']);
}


$db = new Database(new Config);

if (!isset($_GET['p'])) {
    Json::badRequest();
    exit;
}

if (isset($_GET['v'])) {
    $version = intval($_GET['v']);
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
}

$args = explode('/', trim($_GET['p'], '/'));

if ($args[0] == 'news' && count($args) == 2) { # /api/news/<lastModified>
    $model = new NewsModel($db);

    $lastModified = intval($args[1]);
    $now = time();
    $news = $model->get(date('Y-m-d', $now), $lastModified);

    header('Content-Type: application/json');

    if ($binary) {
        echo 'PJ'; //header
        binUnsignedLong($now);
        binUnsignedByte(count($news));

        foreach ($news as $n) {
            binUnsignedByte($n['type']);

            switch($n['type']) {
                case NewsModel::APK:
                    binUnsignedLong($n['value']);
                    break;
                case NewsModel::REPLACEMENTS:
                    binReplacements($n);
                    break;
                case NewsModel::LUCKY_NUMBER:
                    binLuckyNumber($n);
                    break;
                case NewsModel::TIMETABLE:
                    binTimetable($n);
                    break;
            }
        }
        echo 'PJ'; //footer
    }

    else {
        echo '{"timestamp":'.$now.',"news":[';

        foreach ($news as $n) {
            switch ($n['type']) {
                case NewsModel::APK:
                    echo '{"type":"apk","version":"' . $n['value'] . '"},';
                    break;
                case NewsModel::REPLACEMENTS:
                    echo '{"type":"replacements","date":"' . $n['date'] . '","class":"' . $n['class'] . '","lastModified":' . $n['timestamp'] . ',"value":' . $n['value'] . '},';
                    break;
                case NewsModel::LUCKY_NUMBER:
                    echo '{"type":"luckyNumber","date":"' . $n['date'] . '","lastModified":' . $n['timestamp'] . ',"value":' . $n['value'] . '},';
                    break;
                case NewsModel::TIMETABLE:
                    echo '{"type":"timetable","class":"' . $n['class'] . '","lastModified":' . $n['timestamp'] . ',"value":' . json_encode($n['value']) . '},';
                    break;
            }
        }
        echo ']}';
    }
}
//else if (!$binary && $args[0] == 'lucky-numbers' && count($args) == 2) { # /api/lucky-numbers/<date>
//    $date = urldecode($args[1]);
//    $model = new LuckyNumbersModel($db);
//    $ln = $model->get($date);
//
//    if ($ln !== null) {
//        Json::OK($ln);
//    } else {
//        Json::notFound();
//    }
//}
//else if (!$binary && $args[0] == 'replacements' && count($args) == 3) { # /api/replacements/<date>/<class>
//    $date = urldecode($args[1]);
//    $class = urldecode($args[2]);
//
//    $model = new ReplacementsModel($db);
//    $replacements = $model->get($class, $date);
//
//    if ($replacements !== null) {
//        Json::OK($replacements);
//    } else {
//        Json::notFound();
//    }
//}
//else if (!$binary && $args[0] == 'timetables' && count($args) == 1) { # /api/timetables
//    $model = new TimetablesModel($db);
//
//    $timetables = $model->getAll([TimetablesModel::FIELD_CLASS]);
//    Json::OK($timetables);
//}
//else if (!$binary && $args[0] == 'timetables' && count($args) == 2) { # /api/timetables/<class>
//    $class = urldecode($args[1]);
//
//    $model = new TimetablesModel($db);
//    $timetable = $model->get($class);
//
//    if ($timetable !== null) {
//        Json::OK($timetable);
//    } else {
//        Json::notFound();
//    }
//}
else {
    Json::badRequest();
}