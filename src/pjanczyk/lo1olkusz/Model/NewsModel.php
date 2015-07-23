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

//        $stmt = $this->db->select(ReplacementsModel::TABLE, [ReplacementsModel::FIELD_DATE])
//            ->where('class=:class AND lastModified>:since')
//            ->prepare();
//
//        $stmt->bindColumn(':class', $class);
//        $stmt->bindColumn(':since', $since);
//
//        $replacements = $stmt->fetchAll(PDO::FETCH_COLUMN);
//
//        //lucky numbers
//        $stmt = $this->db->select(LuckyNumbersModel::TABLE, [LuckyNumbersModel::FIELD_DATE])
//            ->where('lastModified>:since')
//            ->prepare();
//
//        $stmt->bindColumn(':since', $since);
//
//        $luckyNumbers = $stmt->fetchAll(PDO::FETCH_COLUMN);
//
//        //timetables
//        $stmt = $this->db->select(TimetablesModel::TABLE, ['COUNT(*) > 0'])
//            ->where('class=:class AND lastModified>:since')
//            ->prepare();
//
//        $stmt->bindColumn(':class', $class);
//        $stmt->bindColumn(':since', $since);
//
//        $newTimetables
//
//        $replacements = $stmt->fetchAll(PDO::FETCH_COLUMN);

    }
}