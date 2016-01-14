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

use pjanczyk\lo1olkusz\Auth;
use pjanczyk\lo1olkusz\Controller\Controller;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Model\SettingRepository;
use pjanczyk\lo1olkusz\Model\StatisticRepository;

class SettingController extends Controller
{
    public function __construct()
    {
        Auth::forceLoggingIn();
    }

    public function GET__0()
    {
        $settings = new SettingRepository;

        $apkPath = Config::getInstance()->getApkFilePath();

        $alerts = [];

        if (isset($_FILES['apk']) && $_FILES['apk']['error'] == UPLOAD_ERR_OK) {
            Auth::forceLoggingIn();

            $tempPath = $_FILES['apk']["tmp_name"];
            if (move_uploaded_file($tempPath, $apkPath)) {
                $alerts[] = 'Zaktualizowano plik APK';
            }
        }

        if (isset($_POST['version'])) {
            Auth::forceLoggingIn();

            if ($settings->setVersion($_POST['version'])) {
                $alerts[] = 'Zaktualizowano wersję aplikacji';
            }
        }

        $statistics = new StatisticRepository;

        $template = $this->includeTemplate('dashboard/settings');
        $template->alerts = $alerts;
        $template->version = $settings->getVersion();

        $template->statRest = $statistics->getStatistics(StatisticRepository::REST_API, 14);
        $template->statDownload = $statistics->getStatistics(StatisticRepository::DOWNLOAD, 14);
        $template->statHome = $statistics->getStatistics(StatisticRepository::HOME_PAGE, 14);

        if (file_exists($apkPath)) {
            $template->apkMd5 = md5_file($apkPath);
            $template->apkLastModified = filemtime($apkPath);
        }

        $template->render();
    }

    public function POST__0()
    {
        $this->GET__0();
    }
}
