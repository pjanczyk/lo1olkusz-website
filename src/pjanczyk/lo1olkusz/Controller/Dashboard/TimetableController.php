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
use pjanczyk\lo1olkusz\Model\TimetableRepository;

class TimetableController extends Controller
{
    public function __construct()
    {
        Auth::forceSSL();
    }

    public function GET__0()
    {
        $repo = new TimetableRepository;

        $template = $this->includeTemplate('dashboard/timetable_list');
        $template->timetables = $repo->listAll();
        $template->render();
    }

    public function GET_add_0()
    {
        Auth::forceLoggingIn();

        // angularjs based view
        $this->includeTemplate('dashboard/timetable_view')->render();
    }

    public function GET_edit_1($class)
    {
        Auth::forceLoggingIn();

        // angularjs based view
        $template = $this->includeTemplate('dashboard/timetable_view');
        $template->class = $class;
        $template->render();
    }

    public function GET_import_0()
    {
        Auth::forceLoggingIn();

        // angularjs based view
        $this->includeTemplate('dashboard/timetable_importer')->render();
    }

    public function GET_bells_0()
    {
        Auth::forceLoggingIn();

        // angularjs based view
        $this->includeTemplate('dashboard/timetable_bells')->render();
    }
}

