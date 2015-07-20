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

//Created on 2015-07-14

namespace pjanczyk\lo1olkusz;

class Status {
    /**
     * @param Database $database
     */
    public static function update($database) {
        $news = $database->getLnAndReplacements();
        $config = $database->getConfig();
        $json = json_encode($news + $config);
        FileHelper::updateFile(Config::getDataDir() . '/status', $json);
    }
}