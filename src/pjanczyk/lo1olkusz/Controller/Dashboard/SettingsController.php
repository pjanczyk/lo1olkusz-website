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

namespace pjanczyk\lo1olkusz\Controller\Dashboard;

use pjanczyk\framework\Application;
use pjanczyk\framework\Controller;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Model\SettingRepository;

class SettingsController extends Controller
{
    public function index()
    {
        $model = new SettingRepository;

        /** @var Config $config */
        $config = Application::getConfig();

        $apkPath = $config->getDataDir() . 'apk';

        $alerts = [];

        if (isset($_FILES['apk']) && $_FILES['apk']['error'] == UPLOAD_ERR_OK) {
            $tempPath = $_FILES['apk']["tmp_name"];
            if (move_uploaded_file($tempPath, $apkPath)) {
                $alerts[] = 'Zaktualizowano plik APK';
            }
        }

        if (isset($_POST['version'])) {
            if ($model->setValue('version', $_POST['version'])) {
                $alerts[] = 'Zaktualizowano wersję aplikacji';
            }
        }

        $template = $this->includeTemplate('settings');
        $template->alerts = $alerts;
        $template->version = $model->get('version');
        if (file_exists($apkPath)) {
            $template->apkLastModified = date('Y-m-d H:i:s', filemtime($apkPath));
        }

        $template->render();
    }
}

