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

namespace pjanczyk\Framework;


final class Application
{
    /** @var Application|null */
    private static $instance = null;

    /** @var DatabaseConfig */
    private $dbConfig;
    /** @var array */
    private $route;

    /** @return Application */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setDatabaseConfig(DatabaseConfig $dbConfig)
    {
        $this->dbConfig = $dbConfig;
        return $this;
    }

    public function setRoute(array $route)
    {
        $this->route = $route;
        return $this;
    }

    public function start()
    {
        session_start();
        Database::init($this->dbConfig);
        Auth::init();

        new Router($this->route, function () {
            $this->display404Error();
        });
    }

    public function display404Error()
    {
        header('HTTP/1.0 404 Not Found');
        include '404.html';
        exit;
    }
}