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
use pjanczyk\lo1olkusz\LuckyNumber;

class LuckyNumbersModel
{
    /**
     * @param string $date
     * @return LuckyNumber|null
     */
    public function getByDate($date)
    {
        $stmt = Application::getDb()->prepare(
            'SELECT date, value, UNIX_TIMESTAMP(lastModified) FROM luckyNumbers WHERE date=:date');
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetchObject('pjanczyk\lo1olkusz\LuckyNumber');

        if ($result === false) return null;
        return $result;
    }

    /**
     * @param string $date
     * @param int $lastModified
     * @return array(LuckyNumber)
     */
    public function getByDateAndLastModified($date, $lastModified)
    {
        $stmt = Application::getDb()->prepare(
            'SELECT date, value, UNIX_TIMESTAMP(lastModified) FROM luckyNumbers
WHERE date>=:date AND lastModified>=FROM_UNIXTIME(:lastModified)');

        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':lastModified', $lastModified, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\LuckyNumber');
    }

    /**
     * @return array(LuckyNumber)
     */
    public function getAll()
    {
        $stmt = Application::getDb()->prepare(
            'SELECT date, value, UNIX_TIMESTAMP(lastModified) as lastModified FROM luckyNumbers ORDER BY date ASC');
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\LuckyNumber');
    }

    /**
     * @param string $date
     * @param int $value
     * @return bool
     */
    public function setValue($date, $value)
    {
        $stmt = Application::getDb()->prepare(
            'INSERT INTO luckyNumbers (date, value) VALUES (:date, :value) ON DUPLICATE KEY UPDATE value=:value');

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);

        return $stmt->execute();
    }
}