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

class Config
{
    /** @var Config|null */
    private static $instance = null;

    /** @return Config */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private $dbConfig;

    public function __construct()
    {
        $this->dbConfig = new DatabaseConfig;
        $this->dbConfig->dsn =
            "mysql:host={$_ENV['OPENSHIFT_MYSQL_DB_HOST']}:{$_ENV['OPENSHIFT_MYSQL_DB_PORT']};dbname=lo1olkusz";
        $this->dbConfig->user = $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'];
        $this->dbConfig->password = $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'];
        $this->dbConfig->options = [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'Europe/Warsaw'"];
    }

    public function getDatabaseConfig()
    {
        return $this->dbConfig;
    }

    public function getApkFilePath()
    {
        return $_ENV['OPENSHIFT_DATA_DIR'] . 'apk';
    }

    public function getLogsPath()
    {
        return $_ENV['OPENSHIFT_PHP_LOG_DIR'] . 'cron.log';
    }
}