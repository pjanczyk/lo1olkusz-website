<?php
/**
 * Copyright 2015 Piotr Janczyk
 *
 * This file is part of I LO Olkusz Unofficial App.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace pjanczyk\lo1olkusz\Model;

use PDO;
use pjanczyk\framework\Application;

class TimetableRepository
{
    /**
     * Lists timetables (without their values) ordered by class
     * @return array(Timetable)
     */
    public function listAll()
    {
        $stmt = Application::getDb()->prepare(
            'SELECT class, UNIX_TIMESTAMP(lastModified) FROM timetables ORDER BY class ASC');

        $stmt->execute();

        $obj = new Timetable;
        $stmt->bindColumn(1, $obj->class, PDO::PARAM_STR);
        $stmt->bindColumn(2, $obj->lastModified, PDO::PARAM_INT);

        $results = [];
        while ($stmt->fetch(PDO::FETCH_BOUND)) {
            $results[] = clone $obj;
        }

        return $results;
    }

    /**
     * @param string $class
     * @return Timetable|null
     */
    public function getByClass($class)
    {
        $stmt = Application::getDb()->prepare(
            'SELECT class, value, UNIX_TIMESTAMP(lastModified) as lastModified FROM timetables WHERE class=:class');

        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();

        $obj = new Timetable;
        $stmt->bindColumn(1, $obj->class, PDO::PARAM_STR);
        $stmt->bindColumn(2, $obj->value, PDO::PARAM_STR);
        $stmt->bindColumn(3, $obj->lastModified, PDO::PARAM_INT);

        if ($stmt->fetch(PDO::FETCH_BOUND)) {
            return $obj;
        } else {
            return null;
        }
    }

    /**
     * @param int $lastModified
     * @return array(Timetable)
     */
    public function getByLastModified($lastModified)
    {
        $stmt = Application::getDb()->prepare(
            'SELECT class, value, UNIX_TIMESTAMP(lastModified) as lastModified FROM timetables
WHERE lastModified>=FROM_UNIXTIME(:lastModified)');

        $stmt->bindParam(':lastModified', $lastModified, PDO::PARAM_INT);
        $stmt->execute();

        $obj = new Timetable;
        $stmt->bindColumn(1, $obj->class, PDO::PARAM_STR);
        $stmt->bindColumn(2, $obj->value, PDO::PARAM_STR);
        $stmt->bindColumn(3, $obj->lastModified, PDO::PARAM_INT);

        $results = [];
        while ($stmt->fetch(PDO::FETCH_BOUND)) {
            $results[] = clone $obj;
        }

        return $results;
    }

    /**
     * Inserts or updates a timetable of a $class
     * @param string $class
     * @param string $value
     * @return bool
     */
    public function setValue($class, $value)
    {
        $stmt = Application::getDb()->prepare('INSERT INTO timetables (class, value) VALUES (:class, :value)
ON DUPLICATE KEY UPDATE value=:value');

        $stmt->bindParam(':class', $class);
        $stmt->bindParam(':value', $value);

        return $stmt->execute();
    }

    public function delete($class)
    {
        $stmt = Application::getDb()->prepare('DELETE FROM timetables WHERE class=:class');
        $stmt->bindParam(':class', $class);

        return $stmt->execute();
    }

}