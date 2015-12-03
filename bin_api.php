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

use pjanczyk\framework\Application;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Model\NewsModel;

$binary = true;

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

function binBells($n) {
    binUnsignedLong($n['timestamp']);
    $bells = json_decode($n['value']);
    if (is_array($bells)) {
        binUnsignedByte(count($bells));
        foreach ($bells as $bell) {
            $time = explode(":", $bell);
            binUnsignedByte((int) $time[0]);
            binUnsignedByte((int) $time[1]);
        }
    }
    else {
        binUnsignedByte(0);
    }
}


Application::getInstance()->init(new Config);

function getParameter($name, $defaultValue) {
    if (isset($_GET[$name])) {
        return $_GET[$name];
    } else {
        return $defaultValue;
    }
}


if (!isset($_GET['p'])) {
    Json::badRequest();
    exit;
}

$version = intval(getParameter('v', '0'));
$androidId = getParameter('aid', '0');

$args = explode('/', trim($_GET['p'], '/'));

if ($args[0] == 'news' && count($args) == 2) { # /api/news/<lastModified>
    $model = new NewsModel;

    $lastModified = intval($args[1]);
    $now = time();
    $news = $model->get(date('Y-m-d', $now), $lastModified, $version);

    header('Content-Type: application/json');

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
            case NewsModel::BELLS:
                binBells($n);
                break;
        }
    }
    echo 'PJ'; //footer
}