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

//Created on 2015-07-10

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\lo1olkusz\Config;
use pjanczyk\MVC\Controller;

class CronController extends Controller
{
    public function index()
    {
        $path = Config::getLogDir() . 'cron.log';

        if (isset($_POST['clear-log'])) {
            unlink($path);
            echo 'OK';
        } else if (isset($_POST['run-cron'])) {
            include 'run_cron.php';
        } else {
            $template = $this->includeTemplate('cron');
            $template->logContent = file_exists($path) ? file_get_contents($path) : '';
            $template->render();
        }
    }
}