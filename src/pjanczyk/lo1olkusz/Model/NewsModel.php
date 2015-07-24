<?php

namespace pjanczyk\lo1olkusz\Model;


use PDO;
use pjanczyk\framework\Model;

class NewsModel extends Model {

    const APK = 0;
    const REPLACEMENTS = 1;
    const LUCKY_NUMBER = 2;
    const TIMETABLE = 3;

    /**
     * @param string $class
     * @param string $sinceDate date
     * @param string $sinceLastModified timestamp
     * @return array
     */
    public function get($class, $sinceDate, $sinceLastModified)
    {
        //replacements
        $stmt = $this->db->prepare(
'SELECT 1 AS type, date, value, UNIX_TIMESTAMP(lastModified) AS timestamp FROM replacements WHERE class=:class AND date>=:date AND lastModified>=:lastModified
UNION ALL
SELECT 2, date, value, UNIX_TIMESTAMP(lastModified) FROM luckyNumbers WHERE date>=:date AND lastModified>=:lastModified
UNION ALL
SELECT 3, NULL, value, UNIX_TIMESTAMP(lastModified) FROM timetables WHERE class=:class AND lastModified>=:lastModified
UNION ALL
SELECT 0, NULL, value, NULL FROM settings WHERE name="version"'
        );
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':date', $sinceDate);
        $stmt->bindParam(':lastModified', $sinceLastModified);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * @param string $sinceDate date
     * @param string $sinceLastModified timestamp
     * @return array
     */
    public function get2($sinceDate, $sinceLastModified)
    {
        //replacements
        $stmt = $this->db->prepare(
            'SELECT 1 AS type, date, class, value, UNIX_TIMESTAMP(lastModified) AS timestamp FROM replacements WHERE date>=:date AND lastModified>=:lastModified
UNION ALL
SELECT 2, date, NULL, value, UNIX_TIMESTAMP(lastModified) FROM luckyNumbers WHERE date>=:date AND lastModified>=:lastModified
UNION ALL
SELECT 3, NULL, class, value, UNIX_TIMESTAMP(lastModified) FROM timetables WHERE lastModified>=:lastModified
UNION ALL
SELECT 0, NULL, NULL, value, NULL FROM settings WHERE name="version"'
        );
        $stmt->bindParam(':date', $sinceDate);
        $stmt->bindParam(':lastModified', $sinceLastModified);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}