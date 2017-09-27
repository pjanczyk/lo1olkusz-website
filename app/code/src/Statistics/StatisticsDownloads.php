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

namespace pjanczyk\lo1olkusz\Statistics;

use PDO;
use pjanczyk\lo1olkusz\Database;

class StatisticsDownloads
{
    /**
     * @param string $date in format yyyy-mm-dd
     * @param int $version
     */
    public static function increaseCounter($date, $version)
    {
        $stmt = Database::get()->prepare(
            'INSERT INTO statistics_downloads (date, version, count) VALUES (:date, :version, 1)
ON DUPLICATE KEY UPDATE count=count+1');

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':version', $version, PDO::PARAM_INT);

        $stmt->execute();
    }

    public static function getTotalNumberOfDownloads() {
        $stmt = Database::get()->prepare(
            'SELECT SUM(count) FROM statistics_downloads');
        $stmt->execute();

        return $stmt->fetchColumn();
    }
}