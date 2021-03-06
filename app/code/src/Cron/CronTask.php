<?php
/**
 * Copyright (C) 2016  Piotr Janczyk
 *
 * This file is part of lo1olkusz unofficial app - website.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace pjanczyk\lo1olkusz\Cron;

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\DAO\LuckyNumberRepository;
use pjanczyk\lo1olkusz\DAO\ReplacementsRepository;
use pjanczyk\lo1olkusz\SimpleHtmlDom\SimpleHtmlDom;

class CronTask
{
	const LUCKY_NUMBER_URL = 'http://lo1.olkusz.pl';
	const REPLACEMENT_URL = 'https://lo1olkusz.edu.pl/netstudent/replacements.php';

    public function run()
    {
        Database::init(Config::getInstance()->getDatabaseConfig());

        $this->updateLuckyNumbers();
        $this->updateReplacements();

        echo "done\n";
    }

    private function updateLuckyNumbers()
    {
	    $url = self::LUCKY_NUMBER_URL;
	    $dom = SimpleHtmlDom::fromUrl($url);

	    if ($dom === false) {
		    echo "cannot get {$url}\n";
		    return;
	    }

        $model = new LuckyNumberRepository;
        $parser = new LuckyNumberParser;
        $remote = $parser->getLuckyNumber($dom);
        $this->logErrors('LuckyNumberParser', $parser->getErrors());

        if ($remote !== null) {
            $local = $model->getByDate($remote->date);

            if ($local === null || $remote->value !== $local->value) {
                $model->setValue($remote->date, $remote->value);
                echo "updated ln/{$remote->date}\n";
            }
        }
    }

    private function updateReplacements()
    {
	    $url = self::REPLACEMENT_URL;
	    $dom = SimpleHtmlDom::fromUrl($url);

	    if ($dom === false) {
		    echo "cannot get {$url}\n";
		    return;
	    }

        $model = new ReplacementsRepository;
        $parser = new ReplacementsParser;
        $remoteList = $parser->getReplacements($dom);
        $this->logErrors('ReplacementsParser', $parser->getErrors());

        if ($remoteList !== null) {
            foreach ($remoteList as $remote) {
                $local = $model->getByClassAndDate($remote->class, $remote->date);

                if ($local === null || $remote->value !== $local->value) {
                    $model->setValue($remote->class, $remote->date, $remote->value);
                    echo "updated replacements/{$remote->date}/{$remote->class}\n";
                }
            }
        }
    }

    private function logErrors($tag, $errors)
    {
        if (count($errors) > 0) {
            echo $tag . ":\n";
            foreach ($errors as $error) {
                echo '    ' . $error . "\n";
            }
        }
    }
}