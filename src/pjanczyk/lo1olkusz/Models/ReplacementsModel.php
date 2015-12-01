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

namespace pjanczyk\lo1olkusz\Models;

use PDO;
use pjanczyk\framework\Application;
use pjanczyk\lo1olkusz\Replacements;

class ReplacementsModel
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
        $stmt = Application::getDb()->prepare('SELECT * FROM replacements WHERE date=:date AND class=:class');
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->execute();
        $replacements = $stmt->fetchObject('pjanczyk\lo1olkusz\Replacements');

        if ($replacements === false) return null;
        return $replacements;
    }

    /**
     * @return array(Replacements)
     */
    public function listAll()
    {
        $stmt = Application::getDb()->prepare('SELECT class, date, lastModified FROM replacements
 ORDER BY date DESC, class ASC');

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
        $stmt = Application::getDb()->prepare('INSERT INTO replacements (class, date, value)
VALUES (:class, :date, :value)
ON DUPLICATE KEY UPDATE value=:value');

        $stmt->bindParam(':class', $class, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);

        return $stmt->execute();
    }
}