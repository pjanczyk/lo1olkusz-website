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

namespace pjanczyk\lo1olkusz;

class Auth
{
    private static $ssl;
    private static $authenticated;

    public static $disableSSL;

    public static function init()
    {
        self::$ssl = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on');

        self::$authenticated = false;

        if (self::$ssl || self::$disableSSL) {

            if (isset($_SESSION['authenticated'])) {
                self::$authenticated = true;
                return;
            }

            if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
                $username = $_SERVER['PHP_AUTH_USER'];
                $password = $_SERVER['PHP_AUTH_PW'];

                self::tryLogin($username, $password);
            }
        }
    }

    public static function getProtocol()
    {
        return self::$ssl ? 'https://' : 'http://';
    }

    public static function isAuthenticated()
    {
        return self::$authenticated;
    }

    public static function forceSSL()
    {
        if (!self::$ssl && !self::$disableSSL) {
            header("HTTP/1.0 301 Moved Permanently");
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit;
        }
    }

    public static function forceLoggingIn()
    {
        self::forceSSL();

        if (!self::$authenticated) {
            header('Location: ' . self::getProtocol() . $_SERVER["HTTP_HOST"] . '/dashboard/login?redirect=' . $_SERVER['REQUEST_URI']);
        }
    }

    public static function tryLogin($username, $password)
    {
        $password = sha1($password);

        $stmt = Database::get()->prepare(
            'SELECT EXISTS(SELECT 1 FROM users WHERE username=:username AND password=:password)');

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();

        $valid = $stmt->fetchColumn();
        if ($valid) {
            $_SESSION['authenticated'] = true;
            self::$authenticated = true;
            return true;
        }
        return false;
    }

    public static function logout()
    {
        unset($_SESSION['authenticated']);
        self::$authenticated = false;
    }
}