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
use pjanczyk\lo1olkusz\DAO\ReplacementsRepository;

class ReplacementController extends Controller
{
    public function __construct()
    {
        Auth::forceSSL();
    }

    public function GET__0()
    {
        $repo = new ReplacementsRepository;

        $replacements = $repo->listAll();

        $transposed = [];

        foreach ($replacements as $r) {
            if (!isset($transposed[$r->date])) {
                $transposed[$r->date] = [];
            }

            $transposed[$r->date][] = $r;
        }

        $template = $this->includeTemplate('dashboard/replacements_list');
        $template->transposed = $transposed;
        $template->render();
    }

    public function GET_view_2($date, $class) {
        $model = new ReplacementsRepository;
        $replacements = $model->getByClassAndDate($class, $date);

        if ($replacements == null) {
            header('HTTP/1.0 404 Not Found');
            include '404.html';
        }
        else {
            $template = $this->includeTemplate('dashboard/replacements_view');
            $template->replacements = $replacements;
            $template->render();
        }
    }
}