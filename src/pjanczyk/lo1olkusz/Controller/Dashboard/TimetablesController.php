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

use pjanczyk\Framework\Application;
use pjanczyk\Framework\Controller;
use pjanczyk\lo1olkusz\Model\TimetableRepository;

class TimetablesController extends Controller
{
    public function index()
    {
        $alerts = [];

        $modelTimetables = new TimetableRepository;

        if (isset($_POST['edit'], $_POST['class'], $_POST['timetable'])) {
            $class = $_POST['class'];
            $value = $_POST['timetable'];
            if ($modelTimetables->setValue($class, $value)) {
                $alerts[] = "Zapisano plan lekcji klasy \"{$class}\"";
            }
        } else if (isset($_POST['delete'], $_POST['class'])) {
            $class = $_POST['class'];
            if ($modelTimetables->delete($class)) {
                $alerts[] = "Usunięto plan lekcji klasy \"{$class}\"";
            }
        }

        $template = $this->includeTemplate('dashboard/timetable_list');
        $template->alerts = $alerts;
        $template->timetables = $modelTimetables->listAll();
        $template->render();
    }

    public function add()
    {
        $template = $this->includeTemplate('dashboard/timetable_edit');
        $template->timetable = null;
        $template->render();
    }

    public function edit($class)
    {
        $model = new TimetableRepository;

        $template = $this->includeTemplate('dashboard/timetable_edit');
        $template->timetable = $model->getByClass($class);
        $template->render();
    }

    public function delete($class)
    {
        $model = new TimetableRepository;

        $timetable = $model->getByClass($class);

        if ($timetable === null) {
            Application::getInstance()->display404Error();
            return;
        }

        $template = $this->includeTemplate('dashboard/timetable_delete');
        $template->timetable = $timetable;
        $template->render();
    }
}

