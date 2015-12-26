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

use pjanczyk\Framework\Auth;
use pjanczyk\Framework\Controller;
use pjanczyk\lo1olkusz\Model\NewsService;

class HomeController extends Controller
{
    public function __construct()
    {
        Auth::requireSSL();
    }

    public function index()
    {
        $lastModified = 0;
        $now = time();
        $today = date('Y-m-d', $now);

        $newsService = new NewsService;

        $template = $this->includeTemplate('dashboard/home');
        $template->news = $newsService->getNews($today, $lastModified);
        $template->now = $now;
        $template->render();
    }
}
