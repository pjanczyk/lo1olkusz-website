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

require_once 'classes/Config.php';
require_once 'classes/FileHelper.php';

use \DateTime;

class Logger {

    private $file;

    public function __construct($fileName) {
        $filePath = Config::getLogDir() . '/' . $fileName;
        FileHelper::createParentDirectories($filePath);
        $this->file = fopen($filePath, 'a');
    }

    public function log($tag, $msg) {
        if ($this->file !== false) {
            $date = new DateTime('now', Config::getTimeZone());
            $text = $date->format('Y-m-d H:i:s') . ' [' . $tag . '] ' . $msg . "\n";
            fwrite($this->file, $text);
        }
    }

    public function __destruct() {
        if ($this->file !== false) {
            fclose($this->file);
        }
    }

}