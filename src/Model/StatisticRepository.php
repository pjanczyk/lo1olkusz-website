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

namespace pjanczyk\lo1olkusz\Model;

use PDO;
use pjanczyk\lo1olkusz\Database;

class StatisticRepository
{
    const REST_API = 1;
    const HOME_PAGE = 2;
    const DOWNLOAD = 3;
    const CONTACT_PAGE = 4;

    /**
     * @param int $pageId
     * @param string $date in format yyyy-mm-dd
     * @param int $version
     * @param string $androidId
     */
    public static function increaseVisits($pageId, $date, $version, $androidId)
    {
        $stmt = Database::get()->prepare(
            'INSERT INTO statistics (pageId, date, version, aid, count) VALUES (:pageId, :date, :version, :aid, 1)
ON DUPLICATE KEY UPDATE count=count+1');

        $androidId = sha1($androidId);

        $stmt->bindParam(':pageId', $pageId, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':version', $version, PDO::PARAM_INT);
        $stmt->bindParam(':aid', $androidId, PDO::PARAM_STR);

        $stmt->execute();
    }

    /**
     * @param int $pageId
     * @param int $limit
     * @return array
     */
    public static function getStatistics($pageId, $limit)
    {
        $stmt = Database::get()->prepare(
            'SELECT date, version, count(*) AS uniqueVisits, sum(count) AS visits FROM statistics WHERE pageId=:pageId
GROUP BY date, version
ORDER BY date DESC, version DESC LIMIT :limit');

        $stmt->bindParam(':pageId', $pageId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\Model\Statistic');
    }

}