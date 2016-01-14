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
use pjanczyk\lo1olkusz\Database;

class ReplacementsRepository
{
    /**
     * @param string $class
     * @param string $date
     * @return null|Replacements
     */
    public function getByClassAndDate($class, $date)
    {
        $stmt = Database::get()->prepare(
            'SELECT class, date, value, UNIX_TIMESTAMP(lastModified) AS lastModified FROM replacements
WHERE date=:date AND class=:class');

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();

        $obj = $stmt->fetchObject('pjanczyk\lo1olkusz\Model\Replacements');

        if ($obj === false) return null;
        return $obj;
    }

    /**
     * @param string $date
     * @param int $lastModified
     * @return array(Replacements)
     */
    public function getByDateAndLastModified($date, $lastModified)
    {
        $stmt = Database::get()->prepare(
            'SELECT class, date, value, UNIX_TIMESTAMP(lastModified) AS lastModified FROM replacements
WHERE date>=:date AND lastModified>=FROM_UNIXTIME(:lastModified)');

        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':lastModified', $lastModified, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\Model\Replacements');
    }

    /**
     * Return array of replacements without their values
     * @return array(Replacements)
     */
    public function listAll()
    {
        $stmt = Database::get()->prepare(
            'SELECT class, date, UNIX_TIMESTAMP(lastModified) AS lastModified FROM replacements
ORDER BY date DESC, class ASC');

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\Model\Replacements');
    }

    /**
     * @return int
     */
    public function count()
    {
        $stmt = Database::get()->prepare('SELECT COUNT(*) FROM replacements');
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    /**
     * @param string $class
     * @param string $date
     * @param int $value
     * @return bool
     */
    public function setValue($class, $date, $value)
    {
        $stmt = Database::get()->prepare(
            'INSERT INTO replacements (class, date, value) VALUES (:class, :date, :value)
ON DUPLICATE KEY UPDATE value=:value');

        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindValue(':value', json_encode($value), PDO::PARAM_STR);

        return $stmt->execute();
    }
}