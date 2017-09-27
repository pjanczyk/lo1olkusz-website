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

namespace pjanczyk\lo1olkusz\DAO;

use PDO;
use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Model\LuckyNumber;

class LuckyNumberRepository
{
    /**
     * @param string $date
     * @return LuckyNumber|null
     */
    public function getByDate($date)
    {
        $stmt = Database::get()->prepare(
            'SELECT date, value, UNIX_TIMESTAMP(lastModified) AS lastModified FROM luckyNumbers WHERE date=:date');
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();

        $obj = $stmt->fetchObject('pjanczyk\lo1olkusz\Model\LuckyNumber');

        if ($obj === false) return null;
        return $obj;
    }

    /**
     * @param string $date
     * @param int $lastModified
     * @return array(LuckyNumber)
     */
    public function getByDateAndLastModified($date, $lastModified)
    {
        $stmt = Database::get()->prepare(
            'SELECT date, value, UNIX_TIMESTAMP(lastModified) AS lastModified FROM luckyNumbers
WHERE date>=:date AND lastModified>=FROM_UNIXTIME(:lastModified)');

        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':lastModified', $lastModified, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\Model\LuckyNumber');
    }

    /**
     * @return array(LuckyNumber)
     */
    public function listAll()
    {
        $stmt = Database::get()->prepare(
            'SELECT date, value, UNIX_TIMESTAMP(lastModified) AS lastModified FROM luckyNumbers ORDER BY date DESC');
        $stmt->execute();


        return $stmt->fetchAll(PDO::FETCH_CLASS, 'pjanczyk\lo1olkusz\Model\LuckyNumber');
    }

    /**
     * @param string $date
     * @param int $value
     * @return bool
     */
    public function setValue($date, $value)
    {
        $stmt = Database::get()->prepare(
            'INSERT INTO luckyNumbers (date, value) VALUES (:date, :value) ON DUPLICATE KEY UPDATE value=:value');

        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_INT);

        return $stmt->execute();
    }
}