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
require_once 'classes/ReplacementsProvider.php';
require_once 'classes/LuckyNumberProvider.php';


class CronTask {

    private $logger;

    public function __construct() {
        $this->logger = new Logger('CronTask.log');
    }

    public function run() {
        $this->logger->log('CronTask', 'running task');

        //$dom = file_get_html("http://lo1.olkusz.pl/aktualnosci/zast");
        $dom = file_get_html("zast.html");

        $lnProvider = new LuckyNumberProvider;
        $ln = $lnProvider->getLuckyNumber($dom);
        $this->logErrors('LuckyNumberProvider', $lnProvider->getErrors());

        $replsProvider = new ReplacementsProvider;
        $repls = $replsProvider->getReplacements($dom);
        $this->logErrors('ReplacementsProvider', $lnProvider->getErrors());

        $this->update('ln', $ln);
        $this->update('replacements', $repls);

        $this->logger->log('CronTask', 'completed');
    }

    private function update($what, $array) {
        $json = json_encode($array);
        $relativePath = $what . '/' . $array['date'];
        $filePath = Config::getDataDir() . '/' . $relativePath;

        //make sure all parent directories exist
        FileHelper::createParentDirectories($filePath);

        if (FileHelper::updateFile($filePath, $json)) {
            $this->logger->log('CronTask', 'updated '. $relativePath);
        }
    }

    private function logErrors($tag, $errors) {
        foreach ($errors as $error) {
            $this->logger->log($tag, $error);
        }
    }
}