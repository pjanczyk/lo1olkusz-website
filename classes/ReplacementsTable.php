<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-19
 * Time: 14:50
 */

namespace pjanczyk\lo1olkusz;

use PDO;
use pjanczyk\sql\SqlBuilder;

class ReplacementsTable {

    const FIELD_CLASS = 'class';
    const FIELD_DATE = 'date';
    const FIELD_LAST_MODIFIED = 'lastModified';
    const FIELD_VALUE = 'value';

    /** @varPDO */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * @param string $class
     * @param string $date
     * @return null|Replacements
     */
    public function get($class, $date) {
        $sql = SqlBuilder::select('replacements', [self::FIELD_VALUE, self::FIELD_LAST_MODIFIED])
            ->where('`date`=:date AND `class`=:class')
            ->sql();

        $stmt = $this->db->prepare($sql);

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();
        $replacements = $stmt->fetchObject('pjanczyk\lo1olkusz\Replacements');

        if ($replacements !== false) {
            $replacements->class = $class;
            $replacements->date = $date;

            return $replacements;
        }
        else {
            return null;
        }
    }

    /**
     * @param array $fields array of requested columns
     * @return array(LuckyNumber)
     */
    public function getAll($fields) {
        $sql = SqlBuilder::select('replacements', $fields)
            ->orderAsc('date')
            ->orderAsc('class')
            ->sql();

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\Replacements');;
    }

    /**
     * @param string $class
     * @param string $date
     * @param int $value
     * @return bool
     */
    public function set($class, $date, $value) {
        $stmt = $this->db->prepare('INSERT INTO `replacements` (`class`,`date`,`value`)
VALUES (:class,:date,:value)
ON DUPLICATE KEY UPDATE `value`=:value');

        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);

        return $stmt->execute();
    }
}