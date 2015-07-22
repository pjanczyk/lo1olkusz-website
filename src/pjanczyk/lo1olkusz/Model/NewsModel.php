<?php

namespace pjanczyk\lo1olkusz\Model;


use PDO;
use pjanczyk\framework\Model;

class NewsModel extends Model {

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
'SELECT "replacements", date, value, lastModified FROM replacements WHERE class=:class AND date>=:since AND lastModified>=:lastModified
UNION ALL
SELECT "luckyNumber", date, value, lastModified FROM luckyNumbers WHERE date>=:since AND lastModified>=:lastModified
UNION ALL
SELECT "timetable", NULL, value, lastModified FROM timetables WHERE class=:class AND lastModified>=:lastModified'
        );
        $stmt->bindColumn(':class', $class);
        $stmt->bindColumn(':since', $sinceDate);
        $stmt->bindColumn(':lastModified', $sinceLastModified);

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