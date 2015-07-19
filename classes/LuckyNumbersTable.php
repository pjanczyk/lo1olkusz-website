<?php
/**
 * Created by PhpStorm.
 * User: Piotrek
 * Date: 2015-07-19
 * Time: 14:01
 */

namespace pjanczyk\lo1olkusz;

use PDO;

class LuckyNumbersTable {

    /** @var PDO */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * @param string $date
     * @return LuckyNumber|null
     */
    public function get($date) {
        $stmt = $this->db->prepare('SELECT `lastModified`,`value` FROM `ln` WHERE `date`=:date');
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);

        $ln = new LuckyNumber;
        $ln->date = $date;
        $stmt->bindColumn('lastModified', $ln->lastModified);
        $stmt->bindColumn('value', $ln->value);

        $stmt->execute();

        if ($stmt->fetch(PDO::FETCH_BOUND) !== false) {
            return $ln;
        }
        else {
            return null;
        }
    }

    /**
     * @param string $date
     * @param int $value
     * @return bool
     */
    public function set($date, $value) {
        $stmt = $this->db->prepare('INSERT INTO `ln` (`date`,`value`) VALUES (:date, :value) ON DUPLICATE KEY UPDATE `value`=:value');
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);

        return $stmt->execute();
    }
}