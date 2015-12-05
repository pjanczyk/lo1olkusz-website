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

namespace PiotrJanczyk\lo1olkusz\Model;

use PDO;
use PiotrJanczyk\Framework\Application;

//TODO
class NewsModel
{
    const APK = 0;
    const REPLACEMENTS = 1;
    const LUCKY_NUMBER = 2;
    const TIMETABLE = 3;
    const BELLS = 4;

    /**
     * @param string $sinceDate date
     * @param int $sinceLastModified timestamp
     * @param string $version version of client application
     * @return array
     */
    public function get($sinceDate, $sinceLastModified, $version)
    {
        //replacements
        $stmt = Application::getDb()->prepare(
            'SELECT 1 AS type, date, class, value, UNIX_TIMESTAMP(lastModified) AS timestamp FROM replacements
WHERE date>=:date AND lastModified>=FROM_UNIXTIME(:lastModified)
UNION ALL
SELECT 2, date, NULL, value, UNIX_TIMESTAMP(lastModified) FROM luckyNumbers
WHERE date>=:date AND lastModified>=FROM_UNIXTIME(:lastModified)
UNION ALL
SELECT 3, NULL, class, value, UNIX_TIMESTAMP(lastModified) FROM timetables
WHERE lastModified>=FROM_UNIXTIME(:lastModified)
UNION ALL
SELECT 4, NULL, NULL, value, UNIX_TIMESTAMP(lastModified) FROM bells
WHERE id=0 AND lastModified>=FROM_UNIXTIME(:lastModified)
UNION ALL
SELECT 0, NULL, NULL, value, NULL FROM settings WHERE name="version" AND value>:version'
        );
        $stmt->bindParam(':date', $sinceDate);
        $stmt->bindParam(':lastModified', $sinceLastModified, PDO::PARAM_INT);
        $stmt->bindParam(':version', $version);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}