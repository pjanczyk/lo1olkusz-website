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
     * @param string $sinceDate date
     * @param int $sinceLastModified timestamp
     * @param string $version version of client application
     * @return array
     */
    public function get($sinceDate, $sinceLastModified, $version)
    {
        //replacements
        $stmt = $this->db->prepare(
'SELECT 1 AS type, date, class, value, UNIX_TIMESTAMP(lastModified) AS timestamp FROM replacements
WHERE date>=:date AND lastModified>=FROM_UNIXTIME(:lastModified)
UNION ALL
SELECT 2, date, NULL, value, UNIX_TIMESTAMP(lastModified) FROM luckyNumbers
WHERE date>=:date AND lastModified>=FROM_UNIXTIME(:lastModified)
UNION ALL
SELECT 3, NULL, class, value, UNIX_TIMESTAMP(lastModified) FROM timetables
WHERE lastModified>=FROM_UNIXTIME(:lastModified)
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