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

namespace pjanczyk\lo1olkusz\Controller\Dashboard;

use pjanczyk\lo1olkusz\Auth;
use pjanczyk\lo1olkusz\Controller\Controller;
use pjanczyk\lo1olkusz\DAO\SettingRepository;
use pjanczyk\lo1olkusz\Statistics\StatisticsApi;

class SettingController extends Controller
{
    public function __construct()
    {
        Auth::forceLoggingIn();
    }

    // GET /dashboard/settings
    public function GET__0()
    {
        $settings = new SettingRepository;

        $alerts = [];

        $template = $this->includeTemplate('dashboard/settings');
        $template->alerts = $alerts;
        $template->version = $settings->getVersion();

        $statisticsApi = new StatisticsApi;
        $timeMax = time();
        $timeMin = time() - 13 * 24 * 60 * 60;
        $dateMax = date('Y-m-d', $timeMax);
        $dateMin = date('Y-m-d', $timeMin);

        $template->statisticsApi = $statisticsApi->getNumberOfUsersPerDay($dateMin, $dateMax);

        $template->render();
    }

    // POST /dashboard/settings
    public function POST__0()
    {
        $this->GET__0();
    }
}

