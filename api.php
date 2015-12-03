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

use pjanczyk\framework\Application;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Model\LuckyNumberRepository;
use pjanczyk\lo1olkusz\Model\News;
use pjanczyk\lo1olkusz\Model\ReplacementsRepository;
use pjanczyk\lo1olkusz\Model\SettingRepository;
use pjanczyk\lo1olkusz\Model\TimetableRepository;

function getParameter($name, $defaultValue) {
    if (isset($_GET[$name])) {
        return urldecode($_GET[$name]);
    } else {
        return $defaultValue;
    }
}

$page = getParameter('p', null);
$version = intval(getParameter('v', '0'));
$androidId = getParameter('aid', '0');

if ($page === null) {
    Json::badRequest();
    exit;
}

$args = explode('/', trim($page, '/'));

Application::getInstance()->init(new Config);

if ($args[0] == 'news' && count($args) == 2) { # /api/news/<lastModified>

    $lastModified = intval($args[1]);
    $now = time();

    $today = date('Y-m-d', $now);

    $replacements = new ReplacementsRepository;
    $luckyNumbers = new LuckyNumberRepository;
    $timetables = new TimetableRepository;
    $settings = new SettingRepository;

    $news = new News;

    $news->timetables = $now;
    $news->replacements = $replacements->getByDateAndLastModified($today, $lastModified);
    $news->luckyNumbers = $luckyNumbers->getByDateAndLastModified($today, $lastModified);
    $news->timetables = $timetables->getByLastModified($lastModified);
    $news->version = (int) $settings->get('version');

    Json::OK($news);
}
else if ($args[0] == 'cron' && count($args) == 1) { # /api/cron
    include 'api/cron.php';
}
else if ($args[0] == 'lucky-numbers' && count($args) == 2) { # /api/lucky-numbers/<date>
    $date = urldecode($args[1]);

    $repo = new LuckyNumberRepository;
    $result = $repo->getByDate($date);

    if ($result !== null) {
        Json::OK($result);
    } else {
        Json::notFound();
    }
}
else if ($args[0] == 'replacements' && count($args) == 3) { # /api/replacements/<date>/<class>
    $date = urldecode($args[1]);
    $class = urldecode($args[2]);

    $repo = new ReplacementsRepository;
    $result = $repo->getByClassAndDate($class, $date);

    if ($replacements !== null) {
        Json::OK($replacements);
    } else {
        Json::notFound();
    }
}
else if ($args[0] == 'timetables' && count($args) == 1) { # /api/timetables
    $repo = new TimetableRepository;
    $result = $repo->listAll();

    Json::OK($result);
}
else if ($args[0] == 'timetables' && count($args) == 2) { # /api/timetables/<class>
    $class = urldecode($args[1]);

    $repo = new TimetableRepository;
    $result = $repo->getByClass($class);

    if ($result !== null) {
        Json::OK($result);
    } else {
        Json::notFound();
    }
}
else {
    Json::badRequest();
}