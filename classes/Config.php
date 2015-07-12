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

class Config {
    private static $DATA_DIR;
    private static $LOG_DIR;
    private static $TIMEZONE;

    public static function init() {
        Config::$DATA_DIR = $_ENV['OPENSHIFT_DATA_DIR'];
        Config::$LOG_DIR = $_ENV['OPENSHIFT_PHP_LOG_DIR'];
        Config::$TIMEZONE = new \DateTimeZone('Europe/Warsaw');
    }

    public static function getDataDir() {
        return Config::$DATA_DIR;
    }

    public static function getLogDir() {
        return Config::$LOG_DIR;
    }

    public static function getTimeZone() {
        return Config::$TIMEZONE;
    }

    public static function getUrl() {
        return 'http://lo1.olkusz.pl/aktualnosci/zast';
    }
}

Config::init();
