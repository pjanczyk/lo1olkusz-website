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

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\framework\Controller;

class CronController extends Controller
{
    public function index()
    {
        $this->includeTemplate('cron')->render();

        //$path = Application::getInstance()->getConfig()->getLogDir() . 'cron.log';

//        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
//            unlink($path);
//            echo 'OK';
//        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//            $task = new CronTask;
//            $task->run();
//        } else {
//            $template = $this->includeTemplate('cron');
//            $template->logContent = file_exists($path) ? file_get_contents($path) : '';
//            $template->render();
//        }
    }
}