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

namespace pjanczyk\lo1olkusz\Models;

use PDO;
use pjanczyk\MVC\Model;

class SettingsModel extends Model {

    const TABLE = 'settings';
    const FIELD_NAME = 'name';
    const FIELD_VALUE = 'value';

    public function getAll() {
        $stmt = $this->db->select(self::TABLE, [self::FIELD_NAME, self::FIELD_VALUE])
            ->prepare();

        $stmt->bindColumn(1, $name);
        $stmt->bindColumn(2, $value);
        $stmt->execute();

        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_BOUND)) {
            $result[$name] = $value;
        }

        return $result;
    }

    public function setValue($name, $value) {
        $stmt = $this->db->insertOrUpdate(self::TABLE)
            ->where([self::FIELD_NAME=>':name'])
            ->set([self::FIELD_VALUE=>':value'])
            ->prepare();

        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':value', $value, PDO::PARAM_STR);

        return $stmt->execute();
    }
}