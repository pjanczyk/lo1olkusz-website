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

//Created on 2015-07-15

require_once 'classes/Config.php';
require_once 'classes/Database.php';
require_once 'classes/Status.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Status;

date_default_timezone_set('Europe/Warsaw');

$data = new Database;

$timetablePath = Config::getDataDir() . 'timetable';
$apkPath = Config::getDataDir() . 'apk';

$alerts = [];
$updateStatus = false;

if (isset($_FILES['timetable-file'])
    && $_FILES['timetable-file']['error'] == UPLOAD_ERR_OK) {

    $tmpName = $_FILES['timetable-file']["tmp_name"];
    if (move_uploaded_file($tmpName, $timetablePath)) {
        $alerts[] = 'Changed timetable file';
    }
}
if (isset($_FILES['apk-file'])
    && $_FILES['apk-file']['error'] == UPLOAD_ERR_OK) {

    $tmpName = $_FILES['apk-file']["tmp_name"];
    if (move_uploaded_file($tmpName, $apkPath)) {
        $alerts[] = 'Changed APK file';
    }
}

if (isset($_POST['timetable-version'])) {
    if ($data->setConfigValue('timetable', $_POST['timetable-version'])) {
        $alerts[] = 'Changed timetable version';
        $updateStatus = true;
    }
}
if (isset($_POST['apk-version'])) {
    if ($data->setConfigValue('version', $_POST['apk-version'])) {
        $alerts[] = 'Changed APK version';
        $updateStatus = true;
    }
}

if ($updateStatus) {
    Status::update($data);
    $alerts[] = 'Updated status file';
}

$config = $data->getConfig();

if (isset($config['timetable'])) {
    $timetableVersion = $config['timetable'];
}
if (isset($config['version'])) {
    $apkVersion = $config['version'];
}
if (file_exists($timetablePath)) {
    $timetableFileLastModified = date('Y-m-d H:i:s', filemtime($timetablePath));
}
if (file_exists($apkPath)) {
    $apkFileLastModified = date('Y-m-d H:i:s', filemtime($apkPath));
}


include 'views/settings.php';

