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

class LoginController extends Controller
{
    public function GET__0()
    {
        if (Auth::isAuthenticated()) {
            $this->redirect();
        }

        $alerts = [];

        if (isset($_POST['password'], $_POST['login'])) {

            $login = $_POST['login'];
            $password = $_POST['password'];

            if (Auth::tryLogin($login, $password)) {
                $this->redirect();
            } else {
                $alerts[] = 'Błędne dane';
            }
        }

        $template = $this->includeTemplate('dashboard/login');
        $template->alerts = $alerts;
        $template->render();
    }

    public function POST__0()
    {
        $this->GET__0();
    }

    public function GET_logout_0() {
        Auth::logout();
        header("Location: " . Auth::getProtocol() . $_SERVER["HTTP_HOST"] . '/dashboard/login');
    }

    private function redirect()
    {
        $redirect = '/dashboard';

        if (isset($_GET['redirect'])) {
            $redirect = $_GET['redirect'];
        }

        header("Location: " . Auth::getProtocol() . $_SERVER["HTTP_HOST"] . $redirect);
        exit;
    }
}