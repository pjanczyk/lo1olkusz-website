<?php

namespace pjanczyk\lo1olkusz\Model;

use PDO;
use pjanczyk\framework\Model;
use pjanczyk\lo1olkusz\Replacements;

class ReplacementsModel extends Model
{
    const TABLE = 'replacements';
    const FIELD_CLASS = 'class';
    const FIELD_DATE = 'date';
    const FIELD_LAST_MODIFIED = 'lastModified';
    const FIELD_VALUE = 'value';

    /**
     * @param string $class
     * @param string $date
     * @return null|Replacements
     */
    public function get($class, $date)
    {
        $stmt = $this->db
            ->select(self::TABLE, ['value', 'lastModified'])
            ->where([self::FIELD_DATE => ':date', self::FIELD_CLASS => ':class'])
            ->prepare();

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();
        $replacements = $stmt->fetchObject('pjanczyk\lo1olkusz\Replacements');

        if ($replacements !== false) {
            $replacements->class = $class;
            $replacements->date = $date;

            return $replacements;
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
            ->orderAsc(self::FIELD_DATE)
            ->orderAsc(self::FIELD_CLASS)
            ->prepare();

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\Replacements');
    }

    /**
     * @param string $class
     * @param string $date
     * @param int $value
     * @return bool
     */
    public function set($class, $date, $value)
    {
        $stmt = $this->db->insertOrUpdate(self::TABLE)
            ->where([self::FIELD_CLASS => ':class', self::FIELD_DATE => ':date'])
            ->set([self::FIELD_VALUE => ':value'])
            ->prepare();

        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);

        return $stmt->execute();
    }
}