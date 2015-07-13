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
require_once 'classes/FileHelper.php';
require_once 'simple_html_dom.php';
require_once 'classes/ReplacementsProvider.php';
require_once 'classes/LuckyNumberProvider.php';
require_once 'classes/Data.php';
require_once 'classes/Database.php';


class CronTask {

    /** @var Data */
    private $data;

    public function run() {
        $this->data = new Data(connectToDb());

        $url = Config::getUrl();
        $dom = file_get_html($url);

        if ($dom === false) {
            echo "cannot get {$url}";
        }
        else {

            $lnProvider = new LuckyNumberProvider;
            $ln = $lnProvider->getLuckyNumber($dom);
            $this->logErrors('LuckyNumberProvider', $lnProvider->getErrors());

            $replsProvider = new ReplacementsProvider;
            $repls = $replsProvider->getReplacements($dom);
            $this->logErrors('ReplacementsProvider', $lnProvider->getErrors());

            $this->update(Data::TYPE_LN, 'ln', $ln);
            $this->update(Data::TYPE_REPLACEMENTS, 'replacements', $repls);

            echo "done\n";
        }
    }

    private function update($type, $what, $data) {
        if ($data !== null) {
            $json = json_encode($data);
            $relativePath = $what . '/' . $data['date'];
            $filePath = Config::getDataDir() . '/' . $relativePath;

            //make sure all parent directories exist
            FileHelper::createParentDirectories($filePath);

            if (FileHelper::updateFile($filePath, $json)) {
                $this->data->setLastModified($type, $data['date'], filemtime($filePath));
                echo "updated {$relativePath}\n";
            }
        }
    }

    private function logErrors($tag, $errors) {
        if (count($errors) > 0) {
            echo $tag . ":\n";
            foreach ($errors as $error) {
                echo '    ' . $error . "\n";
            }
        }
    }
}