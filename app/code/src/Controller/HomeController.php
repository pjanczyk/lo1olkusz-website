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

use pjanczyk\lo1olkusz\Statistics\StatisticsApi;
use pjanczyk\lo1olkusz\Statistics\StatisticsDownloads;

class HomeController extends Controller
{
    // GET /
    public function GET__0()
    {
        $statisticsApi = new StatisticsApi;
        $statisticsDownloads = new StatisticsDownloads;

        $template = $this->includeTemplate('home');
        $template->downloadCount = $statisticsDownloads->getTotalNumberOfDownloads();
        $template->userCount = $statisticsApi->getTotalNumberOfUsers();
        $template->render();
    }
}