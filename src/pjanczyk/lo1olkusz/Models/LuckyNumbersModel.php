<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-19
 * Time: 14:01
 */

namespace pjanczyk\lo1olkusz\Models;

use PDO;
use pjanczyk\framework\Model;
use pjanczyk\lo1olkusz\LuckyNumber;

class LuckyNumbersModel extends Model
{
    const TABLE = 'luckyNumbers';
    const FIELD_DATE = 'date';
    const FIELD_LAST_MODIFIED = 'lastModified';
    const FIELD_VALUE = 'value';

    /**
     * @param string $date
     * @return LuckyNumber|null
     */
    public function get($date)
    {
        $stmt = $this->db->select(self::TABLE, [self::FIELD_LAST_MODIFIED, self::FIELD_VALUE])
            ->where([self::FIELD_DATE => ':date'])
            ->prepare();

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);

        $ln = new LuckyNumber;
        $stmt->bindColumn(self::FIELD_VALUE, $ln->value, PDO::PARAM_INT);
        $stmt->bindColumn(self::FIELD_LAST_MODIFIED, $ln->lastModified, PDO::PARAM_STR);

        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_BOUND) !== false) {
            $ln->date = $date;

            return $ln;
        } else {
            return null;
        }
    }

    /**
     * @param array $fields array of requested columns
     * @return array(LuckyNumber)
     */
    public function getAll($fields)
    {
        $stmt = $this->db->select(self::TABLE, $fields)
            ->orderDesc(self::FIELD_DATE)
            ->prepare();

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\LuckyNumber');
    }

    /**
     * @param string $date
     * @param int $value
     * @return bool
     */
    public function setValue($date, $value)
    {
        $stmt = $this->db->insertOrUpdate(self::TABLE)
            ->where([self::FIELD_DATE => ':date'])
            ->set([self::FIELD_VALUE => ':value'])
            ->prepare();

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);

        return $stmt->execute();
    }
}