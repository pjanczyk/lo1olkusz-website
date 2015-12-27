<?php

namespace pjanczyk\Framework;


class Auth
{
    private static $ssl;
    private static $authenticated;

    public static function init()
    {
        self::$ssl = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on")
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')
            || (isset($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on');


        self::$authenticated = false;

        if (self::$ssl) {

            if (isset($_SESSION['authenticated'])) {
                self::$authenticated = true;
                return;
            }

            if (isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])) {
                $username = $_SERVER['PHP_AUTH_USER'];
                $password = sha1($_SERVER['PHP_AUTH_PW']);

                self::tryLogin($username, $password);
            }
        }
    }

    public static function isSSL()
    {
        return self::$ssl;
    }

    public static function isAuthenticated()
    {
        return self::$authenticated;
    }

    public static function requireSSL()
    {
        if(!self::$ssl)
        {
            header("HTTP/1.0 301 Moved Permanently");
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
            exit;
        }
    }

    public static function requireAuthentication()
    {
        self::requireSSL();

        if (!self::$authenticated) {
            header("Location: https://" . $_SERVER["HTTP_HOST"] . '/dashboard/login?redirect=' . $_SERVER['REQUEST_URI']);
        }
    }

    public static function tryLogin($username, $password)
    {
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

    public static function logout() {
        unset($_SESSION['authenticated']);
        self::$authenticated = false;
    }
}