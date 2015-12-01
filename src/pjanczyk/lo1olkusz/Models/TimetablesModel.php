<?php

namespace pjanczyk\lo1olkusz\Models;

use PDO;
use pjanczyk\framework\Application;

class TimetablesModel
{
    const TABLE = 'timetables';
    const FIELD_CLASS = 'class';
    const FIELD_LAST_MODIFIED = 'lastModified';
    const FIELD_VALUE = 'value';

    /**
     * Lists requested fields of timetables ordered by 'class'
     * @return array
     */
    public function listAll()
    {
        $stmt = Application::getDb()->prepare('SELECT class, lastModified FROM timetables ORDER BY class ASC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $class
     * @return \stdClass|null
     */
    public function get($class)
    {
        $stmt = Application::getDb()->prepare('SELECT * FROM timetables WHERE class=:class');

        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();

        $timetable = $stmt->fetchObject();

        if ($timetable === false) return null;
        return $timetable;
    }

    /**
     * Inserts or updates a timetable of a class
     * @param string $class
     * @param string $timetable
     * @return bool
     */
    public function set($class, $timetable)
    {
        $stmt = Application::getDb()->prepare('INSERT INTO timetables (class, value) VALUES (:class, :value)
ON DUPLICATE KEY UPDATE value=:value');

        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':value', $timetable);

        return $stmt->execute();
    }

    public function delete($class)
    {
        $stmt = Application::getDb()->prepare('DELETE FROM timetables WHERE class=:class');
        $stmt->bindParam(':class', $class);

        return $stmt->execute();
    }

}