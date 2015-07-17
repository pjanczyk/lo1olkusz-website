<?php

namespace pjanczyk\lo1olkusz;

use PDO;

class Timetables {

    /** @var PDO */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct($db) {

        $this->db = $db;
    }

    /**
     * Lists class and last modified time of each timetable
     * @return array of arrays ['class','last_modified']
     */
    public function listAll() {
        $stmt = $this->db->prepare('SELECT `class`,`last_modified` FROM `timetable` ORDER BY `class`');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetches a timetable of a class
     * @param string $class
     * @return array|false ['last_modified','value']
     */
    public function get($class) {
        $stmt = $this->db->prepare('SELECT `last_modified`,`value` FROM `timetable` WHERE `class`=:class');
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
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