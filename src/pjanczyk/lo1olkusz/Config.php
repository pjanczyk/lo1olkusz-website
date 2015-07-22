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

//Created on 2015-07-09

namespace pjanczyk\lo1olkusz;

use DateTimeZone;

class Config implements \pjanczyk\framework\Config
{
    private $timezone;

    public function __construct()
    {
        $this->timezone = new DateTimeZone('Europe/Warsaw');
    }

    public function getDataDir()
    {
        return $_ENV['OPENSHIFT_DATA_DIR'];
    }

    public function getLogDir()
    {
        return $_ENV['OPENSHIFT_PHP_LOG_DIR'];
    }

    public function getTimeZone()
    {
        return $this->timezone;
    }

    public function getUrl()
    {
        return 'tests/correct_zast.html';
        //return 'http://lo1.olkusz.pl/aktualnosci/zast';
    }

    public function getDbDSN()
    {
        return "mysql:host={$_ENV['OPENSHIFT_MYSQL_DB_HOST']}:{$_ENV['OPENSHIFT_MYSQL_DB_PORT']};dbname=lo1olkusz";
    }

    public function getDbUser()
    {
        return $_ENV['OPENSHIFT_MYSQL_DB_USERNAME'];
    }

    public function getDbPassword()
    {
        return $_ENV['OPENSHIFT_MYSQL_DB_PASSWORD'];
    }

    public function getDbOptions()
    {
        return [\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = 'Europe/Warsaw'"];
    }

    public function getPagesMap()
    {
        return [
            '' => 'pjanczyk\lo1olkusz\Dashboard\Pages\HomePage',
            'replacements' => 'pjanczyk\lo1olkusz\Dashboard\Pages\ReplacementsPage',
            'lucky-numbers' => 'pjanczyk\lo1olkusz\Dashboard\Pages\LuckyNumbersPage',
            'settings' => 'pjanczyk\lo1olkusz\Dashboard\Pages\SettingsPage',
            'cron' => 'pjanczyk\lo1olkusz\Dashboard\Pages\CronPage'
        ];
    }
}