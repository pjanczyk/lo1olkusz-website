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


class Bells
{
    public $lastModified;
    /** @var string */
    public $value;

    public function __construct()
    {
        settype($this->lastModified, 'int');
        $this->value = json_decode($this->value);
    }

    public static function validateValue($value)
    {
        if (!is_array($value)) return '"value" must be an array';
        foreach ($value as $member) {
            if (!is_array($member) || count($member) !== 2 || !is_string($member[0]) || !is_string($member[1]))
                return 'Each member of the array "value" must be an array containing 2 strings';
        }
        return null;
    }
}