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

//Created on 2015-07-10

namespace pjanczyk\lo1olkusz;

require_once 'classes/Config.php';
require_once 'classes/Logger.php';
require_once 'classes/FileHelper.php';
require_once 'simple_html_dom.php';
require_once 'classes/HtmlReplacementsProvider.php';
require_once 'classes/HtmlLuckyNumberProvider.php';


class CronTask {

    private $logger;

    public function __construct() {
        $this->logger = new Logger('CronTask.log');
    }

    public function run() {
        //$dom = file_get_html("http://lo1.olkusz.pl/aktualnosci/zast");
        $dom = file_get_html("zast.html");

        $lnProvider = new HtmlLuckyNumberProvider;
        $ln = $lnProvider->getLuckyNumber($dom);

        $replsProvider = new HtmlReplacementsProvider;
        $repls = $replsProvider->getReplacements($dom);

        $this->update('ln', $ln);
        $this->update('replacements', $repls);
    }

    private function update($what, $array) {
        $json = json_encode($array);

        $filePath = Config::getDataDir() . '/' . $what . '/' . $array['date'];

        //make sure all parent directories exist
        FileHelper::createParentDirectories($filePath);

        if (FileHelper::updateFile($filePath, $json)) {
            $this->logger->log($what, "modified {$filePath}");
        }
        else {
            $this->logger->log($what, "up-to-date {$filePath}");
        }
    }
}