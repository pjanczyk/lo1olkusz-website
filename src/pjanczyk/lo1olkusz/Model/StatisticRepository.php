<?php

namespace pjanczyk\lo1olkusz\Model;

use PDO;
use pjanczyk\Framework\Database;

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