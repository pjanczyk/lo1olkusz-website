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

class StatisticsApi
{
    /**
     * @param string $date in format yyyy-mm-dd
     * @param int $version
     * @param string $aid
     */
    public static function increaseCounter($date, $version, $aid)
    {
        $stmt = Database::get()->prepare(
            'INSERT INTO statistics_api (date, version, uid, count) VALUES (:date, :version, :uid, 1)
ON DUPLICATE KEY UPDATE count=count+1');

        if (strlen($aid) === 16) {
            $aid = sha1($aid);
        }
        else {
            $aid = "'$aid'";
        }

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':version', $version, PDO::PARAM_INT);
        $stmt->bindParam(':uid', $aid, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * @param string $dateMin in format yyyy-mm-dd
     * @param string $dateMax in format yyyy-mm-dd (optional)
     * @return int Number of unique users at a time between $dateMin and $dateMax
     */
    public static function getNumberOfUsers($dateMin, $dateMax=null)
    {
        if ($dateMax === null) {
            $dateMax = $dateMin;
        }

        $stmt = Database::get()->prepare(
            'SELECT COUNT(DISTINCT uid) FROM statistics_api WHERE date BETWEEN :dateMin AND :dateMax');

        $stmt->bindParam(':dateMin', $dateMin, \PDO::PARAM_STR);
        $stmt->bindParam(':dateMax', $dateMax, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * @param string $dateMin in format yyyy-mm-dd
     * @param string $dateMax in format yyyy-mm-dd (optional)
     * @return int Number of unique users at a time between $dateMin and $dateMax
     */
    public static function getNumberOfUsersPerDay($dateMin, $dateMax=null)
    {
        if ($dateMax === null) {
            $dateMax = $dateMin;
        }

        $stmt = Database::get()->prepare(
            'SELECT date, COUNT(DISTINCT uid) AS users FROM statistics_api WHERE date BETWEEN :dateMin AND :dateMax
GROUP BY date
ORDER BY date DESC');

        $stmt->bindParam(':dateMin', $dateMin, \PDO::PARAM_STR);
        $stmt->bindParam(':dateMax', $dateMax, \PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @return int Number of unique users
     */
    public static function getTotalNumberOfUsers()
    {
        $stmt = Database::get()->prepare(
            'SELECT COUNT(DISTINCT uid) FROM statistics_api');

        $stmt->execute();

        return $stmt->fetchColumn();
    }

}