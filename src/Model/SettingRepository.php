<?php
/**
 * Copyright (C) 2016  Piotr Janczyk
 *
 * This file is part of lo1olkusz unofficial app - website.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace pjanczyk\lo1olkusz\Model;

use PDO;
use pjanczyk\lo1olkusz\Database;

class SettingRepository
{
    /**
     * @param string $name
     * @return string
     */
    public function get($name)
    {
        $stmt = Database::get()->prepare('SELECT value FROM settings WHERE name=:name');
        $stmt->bindParam(':name', $name);
        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * @param string $name
     * @param string $value
     * @return bool True if success
     */
    public function setValue($name, $value)
    {
        $stmt = Database::get()->prepare('INSERT INTO settings (name, value) VALUES (:name, :value)
ON DUPLICATE KEY UPDATE value=:value');

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return (int) $this->get('version');
    }

    /**
     * @param int $version
     * @return bool
     */
    public function setVersion($version)
    {
        return $this->setValue('version', $version);
    }
}