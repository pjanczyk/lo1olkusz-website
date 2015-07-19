<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-19
 * Time: 14:50
 */

namespace pjanczyk\lo1olkusz;

use PDO;

class ReplacementsTable {

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
        $stmt = $this->db->prepare('SELECT `value`,`lastModified` FROM `replacements`
WHERE `date`=:date AND `class`=:class');

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);

        $replacements = new Replacements;
        $replacements->class = $class;
        $replacements->date = $date;
        $stmt->bindColumn('value', $replacements->value);
        $stmt->bindColumn('lastModified', $replacements->lastModified);
        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_BOUND) !== false) {
            return $replacements;
        }
        else {
            return null;
        }
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