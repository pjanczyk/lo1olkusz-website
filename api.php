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
use pjanczyk\lo1olkusz\Model\LuckyNumbersModel;
use pjanczyk\lo1olkusz\Model\NewsModel;
use pjanczyk\lo1olkusz\Model\ReplacementsModel;
use pjanczyk\lo1olkusz\Model\TimetablesModel;

function binUnsignedByte($int) {
    echo pack('C', (int)$int);
}

function binUnsignedLong($int) {
    echo pack('N', (int)$int);
}

function binString($string) {
    binUnsignedLong(strlen($string));
    echo $string;
}

function binDate($date) {
    if (strlen($date) === 10) {
        echo pack('S', (int)substr($date, 0, 4));
        echo pack('C', (int)substr($date, 5, 2));
        echo pack('C', (int)substr($date, 8, 2));
    }
    echo pack('N', 0);
}


$db = new Database(new Config);

if (!isset($_GET['p'])) {
    Json::badRequest();
    exit;
}

$args = explode('/', trim($_GET['p'], '/'));

if ($args[0] == 'news' && count($args) == 3) { # /api/news/<class>/<lastModified>
    $model = new NewsModel($db);

    $class = urldecode($args[1]);
    $lastModified = intval($args[2]);
    $now = time();
    $news = $model->get($class, date('Y-m-d H:i:s', $now), $lastModified);

    header('Content-Type: application/json');

    echo '{"news":[';

    foreach ($news as $n) {
        switch($n['type']) {
            case NewsModel::APK:
                echo '{"type":"apk","version":"'.$n['value'].'"},';
                break;
            case NewsModel::REPLACEMENTS:
                echo '{"type":"replacements","date":"'.$n['date'].'","lastModified":'.$n['timestamp'].',"value":'.$n['value'].'},';
                break;
            case NewsModel::LUCKY_NUMBER:
                echo '{"type":"luckyNumber","date":"'.$n['date'].'","lastModified":'.$n['timestamp'].',"value":'.$n['value'].'},';
                break;
            case NewsModel::TIMETABLE:
                echo '{"type":"timetable","lastModified":'.$n['timestamp'].',"value":'.json_encode($n['value']).'},';
                break;
        }
    }

    echo '],"timestamp":'.$now.'}';
}
else if ($args[0] == 'news-bin' && count($args) == 3) { # /api/news/<class>/<lastModified>
    $model = new NewsModel($db);

    $class = urldecode($args[1]);
    $lastModified = intval($args[2]);
    $now = time();
    $news = $model->get($class, date('Y-m-d H:i:s', $now), $lastModified);

    header('Content-Type: application/json');

    echo 'PJ'; //header
    binUnsignedLong($now);

    foreach ($news as $n) {
        binUnsignedByte($n['type']);

        switch($n['type']) {
            case NewsModel::APK:
                binUnsignedLong($n['timestamp']);
                binString($n['value']);
                break;
            case NewsModel::REPLACEMENTS:
                binUnsignedLong($n['timestamp']);
                binDate($n['date']);
                $replacements = json_decode($n['value']);
                binUnsignedLong(count($replacements));
                foreach ($replacements as $h=>$v) {
                    binUnsignedByte($h);
                    binString($v);
                }
                break;
            case NewsModel::LUCKY_NUMBER:
                binUnsignedLong($n['timestamp']);
                binDate($n['date']);
                binUnsignedLong($n['value']);
                break;
            case NewsModel::TIMETABLE:
                binUnsignedLong($n['timestamp']);
                binString($n['value']);
                break;
        }
    }

    echo 'PJ'; //footer
}
else if ($args[0] == 'lucky-numbers' && count($args) == 2) { # /api/lucky-numbers/<date>
    $date = urldecode($args[1]);

    $model = new LuckyNumbersModel($db);
    $ln = $model->get($date);

    if ($ln !== null) {
        Json::OK($ln);
    } else {
        Json::notFound();
    }
}
else if ($args[0] == 'replacements' && count($args) == 3) { # /api/replacements/<date>/<class>
    $date = urldecode($args[1]);
    $class = urldecode($args[2]);

    $model = new ReplacementsModel($db);
    $replacements = $model->get($class, $date);

    if ($replacements !== null) {
        Json::OK($replacements);
    } else {
        Json::notFound();
    }
}
else if ($args[0] == 'timetables' && count($args) == 1) { # /api/timetables
    $model = new TimetablesModel($db);

    $timetables = $model->getAll([TimetablesModel::FIELD_CLASS]);
    Json::OK($timetables);
}
else if ($args[0] == 'timetables' && count($args) == 2) { # /api/timetables/<class>
    $class = urldecode($args[1]);

    $model = new TimetablesModel($db);
    $timetable = $model->get($class);

    if ($timetable !== null) {
        Json::OK($timetable);
    } else {
        Json::notFound();
    }
}
else {
    Json::badRequest();
}