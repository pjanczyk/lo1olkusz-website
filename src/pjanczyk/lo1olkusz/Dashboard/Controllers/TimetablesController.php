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
use pjanczyk\lo1olkusz\Models\TimetablesModel;

class TimetablesController extends Controller
{
    public function index()
    {
        $alerts = [];

        $modelTimetables = new TimetablesModel;

        if (isset($_POST['edit'], $_POST['class'], $_POST['timetable'])) {
            $class = $_POST['class'];
            $value = $_POST['timetable'];
            if ($modelTimetables->set($class, $value)) {
                $alerts[] = "Saved timetable of \"{$class}\"";
            }
        } else if (isset($_POST['delete'], $_POST['class'])) {
            $class = $_POST['class'];
            if ($modelTimetables->delete($class)) {
                $alerts[] = "Deleted timetable of \"{$class}\"";
            }
        }

        $template = $this->includeTemplate('settings');
        $template->alerts = $alerts;
        $template->timetables = $modelTimetables->listAll();
        $template->render();
    }

    public function add()
    {
        $template = $this->includeTemplate('timetable_edit');
        $template->timetable = null;
        $template->render();
    }

    public function edit($class)
    {
        $model = new TimetablesModel;

        $template = $this->includeTemplate('timetable_edit');
        $template->timetable = $model->get($class);
        $template->render();
    }

    public function delete($class)
    {
        $model = new TimetablesModel;

        $timetable = $model->get($class);

        if ($timetable === null) {
            http404();
            return;
        }

        $template = $this->includeTemplate('timetable_delete');
        $template->timetable = $timetable;
        $template->render();
    }
}

