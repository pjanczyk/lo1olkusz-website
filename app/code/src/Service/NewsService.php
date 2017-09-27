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

namespace pjanczyk\lo1olkusz\Service;

use pjanczyk\lo1olkusz\DAO\BellsRepository;
use pjanczyk\lo1olkusz\DAO\LuckyNumberRepository;
use pjanczyk\lo1olkusz\DAO\ReplacementsRepository;
use pjanczyk\lo1olkusz\DAO\SettingRepository;
use pjanczyk\lo1olkusz\DAO\TimetableRepository;
use pjanczyk\lo1olkusz\Model\News;

class NewsService
{
    /**
     * @param string $date in format yyyy-mm-dd
     * @param int $lastModified
     * @return News
     */
    public function getNews($date, $lastModified)
    {
        $now = time();

        $replacements = new ReplacementsRepository;
        $luckyNumbers = new LuckyNumberRepository;
        $timetables = new TimetableRepository;
        $bells = new BellsRepository;
        $settings = new SettingRepository;

        $news = new News;
        $news->timestamp = $now;
        $news->replacements = $replacements->getByDateAndLastModified($date, $lastModified);
        $news->luckyNumbers = $luckyNumbers->getByDateAndLastModified($date, $lastModified);
        $news->timetables = $timetables->getByLastModified($lastModified);
        $news->bells = $bells->getByLastModified($lastModified);
        $news->version = (int)$settings->get('version');

        return $news;
    }
}