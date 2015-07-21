<?php

namespace pjanczyk\lo1olkusz\Models;

use PDO;
use pjanczyk\MVC\Model;

class TimetablesModel extends Model
{
    const TABLE = 'timetables';
    const FIELD_CLASS = 'class';
    const FIELD_LAST_MODIFIED = 'lastModified';
    const FIELD_VALUE = 'value';

    /**
     * Lists requested fields of timetables ordered by 'class'
     * @param array $fields array of requested columns
     * @return array
     */
    public function getAll($fields)
    {
        $stmt = $this->db->select(self::TABLE, $fields)
            ->orderAsc(self::FIELD_CLASS)
            ->prepare();

        $stmt->execute();

        if (count($fields) == 1) {
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } else {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }


    /**
     * @param string $class
     * @return \stdClass|null
     */
    public function get($class)
    {
        $stmt = $this->db->select(self::TABLE, [self::FIELD_VALUE, self::FIELD_LAST_MODIFIED])
            ->where([self::FIELD_CLASS => ':class'])
            ->prepare();

        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();

        $timetable = $stmt->fetchObject();

        if ($timetable !== false) {
            $timetable->class = $class;

            return $timetable;
        } else {
            return null;
        }
    }

    /**
     * Inserts or updates a timetable of a class
     * @param string $class
     * @param string $timetable
     * @return bool
     */
    public function set($class, $timetable)
    {
        $stmt = $this->db->insertOrUpdate(self::TABLE)
            ->where([self::FIELD_CLASS => ':class'])
            ->set([self::FIELD_VALUE => ':value'])
            ->prepare();

        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':value', $timetable);

        return $stmt->execute();
    }

    public function delete($class)
    {
        $stmt = $this->db->delete(self::TABLE)
            ->where([self::FIELD_CLASS => ':class'])
            ->prepare();

        $stmt->bindParam(':class', $class);

        return $stmt->execute();
    }

}