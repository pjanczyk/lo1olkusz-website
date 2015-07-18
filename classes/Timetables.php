<?php

namespace pjanczyk\lo1olkusz;

use PDO;

class Timetables {

    const FIELD_CLASS = 'class';
    const FIELD_LAST_MODIFIED = 'last_modified';
    const FIELD_VALUE = 'value';

    /** @var PDO */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct($db) {

        $this->db = $db;
    }

    /**
     * Lists requested fields of timetables ordered by 'class'
     * @param array $fields array of requested columns
     * @return array
     */
    public function getAll($fields) {
        if (count($fields) == 0) {
            new \InvalidArgumentException('$fields cannot be an empty array');
        }
        $f = '`'.implode('`,`', $fields).'`';
        $stmt = $this->db->prepare('SELECT '.$f.' FROM `timetable` ORDER BY `class`');
        $stmt->execute();

        if (count($fields) == 1) {
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Fetches a timetable of a class
     * @param string $class
     * @return array|false ['class','last_modified','value']
     */
    public function get($class) {
        $stmt = $this->db->prepare('SELECT `last_modified`,`value` FROM `timetable` WHERE `class`=:class');
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();
        $timetable = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($timetable !== false) {
            $timetable['class'] = $class;
        }

        return $timetable;
    }

    /**
     * Returns last modification time of a timetable of a class
     * @param string $class
     * @return string|false
     */
    public function getLastModified($class) {
        $stmt = $this->db->prepare('SELECT `last_modified` FROM `timetable` WHERE `class`=:class');
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * Inserts or updates a timetable of a class
     * @param string $class
     * @param string $timetable
     * @return bool
     */
    public function set($class, $timetable) {
        $stmt = $this->db->prepare('INSERT INTO `timetable` (`class`,`value`) VALUES (:class, :value)
ON DUPLICATE KEY UPDATE `value`=:value');
        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':value', $timetable);

        return $stmt->execute();
    }

    public function delete($class) {
        $stmt = $this->db->prepare('DELETE FROM `timetable` WHERE `class`=:class');
        $stmt->bindParam(':class', $class);

        return $stmt->execute();
    }

}