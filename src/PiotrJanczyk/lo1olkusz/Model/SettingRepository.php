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

namespace PiotrJanczyk\lo1olkusz\Model;

use PDO;
use PiotrJanczyk\Framework\Application;

class SettingRepository
{
    /**
     * @param string $name
     * @return string
     */
    public function get($name)
    {
        $stmt = Application::getDb()->prepare('SELECT value FROM settings WHERE name=:name');
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
        $stmt = Application::getDb()->prepare('INSERT INTO settings (name, value) VALUES (:name, :value)
ON DUPLICATE KEY UPDATE value=:value');

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);

        return $stmt->execute();
    }
}