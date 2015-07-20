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

require_once 'src/Config.php';
require_once 'src/SqlBuilder.php';

use PDO;
use pjanczyk\sql\SqlBuilder;

/*
CREATE TABLE IF NOT EXISTS `data` (
  `type` tinyint(4) NOT NULL,
  `date` date NOT NULL,
  `last_modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`type`,`date`)
)
 */

class Database {
    const TYPE_LN = 1;
    const TYPE_REPLACEMENTS = 2;

    public static function connect() {
        return new PDO(Config::getDbDSN(), Config::getDbUser(), Config::getDbPassword());
    }

    /** @var PDO */
    private $db;

    /**
     * Opens connection to the database
     */
    public function __construct() {
        $this->db = new PDO(Config::getDbDSN(), Config::getDbUser(), Config::getDbPassword());
    }

    /**
     * @param string $table
     * @param array $columns
     * @return \pjanczyk\sql\internal\SelectBuilder
     */
    public function select($table, $columns) {
        return SqlBuilder::select($table, $columns);
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

    public function setConfigValue($name, $value) {
        $stmt = $this->db->prepare('INSERT INTO `config` (`name`,`value`) VALUES (:name,:value) ON DUPLICATE KEY UPDATE `value`=:value');
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);

        return $stmt->execute();
    }
}