<?php
/**
 * Copyright (C) 2016  Piotr Janczyk
 *
 * This file is part of lo1olkusz unofficial app - website.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace pjanczyk\lo1olkusz\Controller;

use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Model\SettingRepository;
use pjanczyk\lo1olkusz\Model\StatisticRepository;

class DownloadController extends Controller
{
    public function GET__0()
    {
        $settings = new SettingRepository;
        $version = $settings->getVersion();
        $statistics = new StatisticRepository;
        $statistics->increaseVisits(StatisticRepository::DOWNLOAD, date('Y-m-d'), $version, '');

        $path = Config::getInstance()->getApkFilePath();

        if (file_exists($path)) {
            header('Content-Type: application/vnd.android.package-archive');
            header('Content-Disposition: attachment; filename="lo1olkusz-app.apk"');
            readfile($path);
        }
        else {
            header('HTTP/1.0 404 Not Found');
            readfile('html/404.html');
        }
    }
}