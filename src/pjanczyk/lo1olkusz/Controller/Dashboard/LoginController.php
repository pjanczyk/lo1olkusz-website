<?php
/**
 * Created by PhpStorm.
 * User: Piotr
 * Date: 25.12.2015
 * Time: 11:34
 */

namespace pjanczyk\lo1olkusz\Controller\Dashboard;

use pjanczyk\Framework\Auth;
use pjanczyk\Framework\Controller;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::isAuthenticated()) {
            $this->redirect();
        }

        $alerts = [];

        if (isset($_POST['password'], $_POST['login'])) {

            $login = $_POST['login'];
            $password = sha1($_POST['password']);

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

    public function logout() {
        Auth::logout();
        header("Location: https://" . $_SERVER["HTTP_HOST"] . '/dashboard/login');
    }

    private function redirect()
    {
        $redirect = '/dashboard';

        if (isset($_GET['redirect'])) {
            $redirect = $_GET['redirect'];
        }

        header("Location: https://" . $_SERVER["HTTP_HOST"] . $redirect);
        exit;
    }
}