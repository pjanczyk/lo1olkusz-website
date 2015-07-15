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

//Created on 2015-07-13

namespace pjanczyk\lo1olkusz;

use PDO;
use DateTime;

/*
CREATE TABLE IF NOT EXISTS `data` (
  `type` tinyint(4) NOT NULL,
  `date` date NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`type`,`date`)
)
 */

class Data {
    const TYPE_LN = 1;
    const TYPE_REPLACEMENTS = 2;

    /** @var PDO */
    private $db;

    /**
     * @param PDO $db
     */
    public function __construct($db) {
        $this->db = $db;
    }

    /**
     * Sets `last_modification` of data with specific type and date
     * @param int $type TYPE_LN, TYPE_REPLACEMENTS or TYPE_TIMETABLE
     * @param string $date
     * @param int $lastModified timestamp to be set
     * @return bool
     */
    public function setLastModified($type, $date, $lastModified) {
        $stmt = $this->db->prepare('INSERT INTO `data` (`type`,`date`,`last_modified`) VALUES (:type, :date, FROM_UNIXTIME(:last_modified)) ON DUPLICATE KEY UPDATE `last_modified`=FROM_UNIXTIME(:last_modified)');
        $stmt->bindParam(':type', $type, PDO::PARAM_INT);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':last_modified', $lastModified, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function getLnAndReplacements() {
        $stmt = $this->db->prepare('SELECT `type`,`date`,`last_modified` FROM `data` WHERE `date` >= :date');
        $now = new DateTime('now', Config::getTimeZone());
        $stmt->bindValue(':date', $now->format('Y-m-d'), PDO::PARAM_STR);;
        $stmt->bindColumn(1, $typeId);
        $stmt->bindColumn(2, $date);
        $stmt->bindColumn(3, $lastModified);
        $stmt->execute();

        $result = ['ln' => [], 'replacements' => []];
        while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
            switch ($typeId) {
                case self::TYPE_LN:
                    $typeName = 'ln';
                    break;
                case self::TYPE_REPLACEMENTS;
                    $typeName = 'replacements';
                    break;
                default:
                    continue;
            }
            $result[$typeName][] = ['date' => $date, 'last_modified' => $lastModified];
        }

        return $result;
    }

    public function getConfig() {
        $stmt = $this->db->prepare('SELECT `name`,`value` FROM `config`');
        $stmt->bindColumn(1, $name);
        $stmt->bindColumn(2, $value);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
            $result[$name] = $value;
        }

        return $result;
    }
}