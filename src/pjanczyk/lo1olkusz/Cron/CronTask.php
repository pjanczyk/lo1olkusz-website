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

require_once 'libs/simple_html_dom.php';

use DateTime;
use pjanczyk\lo1olkusz\Model\LuckyNumbersModel;
use pjanczyk\lo1olkusz\Model\ReplacementsModel;


class CronTask {

    /** @var \PDO */
    private $db;

    public function run() {
        $now = new DateTime('now', Config::getTimeZone());
        $forceUpdateStatus = ($now->format('Ym') == '0000');

        $this->db = Database::connect();

        $url = Config::getUrl();
        $dom = file_get_html($url);

        if ($dom === false) {
            echo "cannot get {$url}\n";
        }
        else {
            $lnProvider = new LuckyNumberProvider;
            $webLn = $lnProvider->getLuckyNumber($dom);
            $this->logErrors('LuckyNumberProvider', $lnProvider->getErrors());
            var_dump($webLn);
            if ($webLn !== null) {
                $lnMgr = new LuckyNumbersModel($this->db);
                $savedLn = $lnMgr->get($webLn->date);
                var_dump($savedLn);
                if ($savedLn === null || $webLn->value !== $savedLn->value) {
                    $lnMgr->setValue($webLn->date, $webLn->value);
                    echo "updated ln/{$webLn->date}\n";
                }
            }

            $replsProvider = new ReplacementsProvider;
            $webReplacements = $replsProvider->getReplacements($dom);
            $this->logErrors('ReplacementsProvider', $lnProvider->getErrors());
            if ($webReplacements !== null) {
                $replsMgr = new ReplacementsModel($this->db);

                foreach ($webReplacements as $webReplacement) {
                    $savedReplacement = $replsMgr->get($webReplacement->class, $webReplacement->date);
                    if ($savedReplacement === null || $webReplacement->value !== $savedReplacement->value) {
                        $replsMgr->set($webReplacement->class, $webReplacement->date, $webReplacement->value);
                        echo "updated replacements/{$webReplacement->date}/{$webReplacement->class}\n";
                    }
                }
            }
//            if ($forceUpdateStatus || !file_exists(Config::getDataDir() . '/status')) {
//                Status::update($this->data);
//                echo "updated status\n";
//            }

            echo "done\n";
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