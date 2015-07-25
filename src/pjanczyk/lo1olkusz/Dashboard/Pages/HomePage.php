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

//Created on 2015-07-13

namespace pjanczyk\lo1olkusz\Dashboard\Pages;

use pjanczyk\framework\Page;
use pjanczyk\lo1olkusz\Model\NewsModel;

class HomePage extends Page
{
    public function index()
    {
        $this->last_modified(0);
    }

    public function last_modified($timestamp)
    {
        $model = new NewsModel($this->db);

        $lastModified = intval($timestamp);
        $now = date('Y-m-d');
        $news = $model->get2($now, $lastModified);

        $template = $this->includeTemplate('home');
        $template->now = $now;
        $template->news = $news;
        $template->render();
    }
}
