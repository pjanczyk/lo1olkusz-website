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
use pjanczyk\Framework\Database;

class BellsRepository
{
    /**
     * @return Bells|null
     */
    public function get()
    {
        $stmt = Database::get()->prepare(
            'SELECT value, UNIX_TIMESTAMP(lastModified) AS lastModified FROM bells LIMIT 1');

        $stmt->execute();

        $obj = $stmt->fetchObject('pjanczyk\lo1olkusz\Model\Bells');

        if ($obj === false) return null;
        return $obj;
    }

    /**
     * @param int $lastModified
     * @return Bells|null
     */
    public function getByLastModified($lastModified)
    {
        $stmt = Database::get()->prepare(
            'SELECT value, UNIX_TIMESTAMP(lastModified) AS lastModified FROM bells
WHERE lastModified>=FROM_UNIXTIME(:lastModified) LIMIT 1');

        $stmt->bindParam(':lastModified', $lastModified, PDO::PARAM_INT);
        $stmt->execute();

        $obj = $stmt->fetchObject('pjanczyk\lo1olkusz\Model\Bells');

        if ($obj === false) return null;
        return $obj;
    }

    /**
     * Updates bells
     * @param array $value
     * @return bool
     */
    public function set($value)
    {
        $stmt = Database::get()->prepare('UPDATE bells SET value=:value LIMIT 1');
        $stmt->bindValue(':value', json_encode($value));
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}