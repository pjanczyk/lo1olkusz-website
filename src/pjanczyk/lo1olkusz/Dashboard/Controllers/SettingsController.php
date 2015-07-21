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

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Model\SettingsModel;
use pjanczyk\MVC\Controller;
use pjanczyk\sql\Database;

class SettingsController extends Controller {

    public function index() {
        global $apkVersion;
        global $apkFileLastModified;

        $db = new Database;
        $model = new SettingsModel($db);

        $apkPath = Config::getDataDir() . 'apk';

        $alerts = [];

        if (isset($_FILES['apk-file'])
            && $_FILES['apk-file']['error'] == UPLOAD_ERR_OK) {

            $tmpName = $_FILES['apk-file']["tmp_name"];
            if (move_uploaded_file($tmpName, $apkPath)) {
                $alerts[] = 'Changed APK file';
            }
        }

        if (isset($_POST['apk-version'])) {
            if ($model->setValue('version', $_POST['apk-version'])) {
                $alerts[] = 'Changed APK version';
            }
        }

        $config = $model->getAll();

        if (isset($config['version'])) {
            $apkVersion = $config['version'];
        }
        if (file_exists($apkPath)) {
            $apkFileLastModified = date('Y-m-d H:i:s', filemtime($apkPath));
        }

        $this->includeTemplate('settings');
    }
}

