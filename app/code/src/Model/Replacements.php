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

class Replacements
{
    /** @var string */
    public $class;
    /** @var string */
    public $date;
    /** @var array */
    public $value;
    /** @var int */
    public $lastModified;

    public function __construct()
    {
        settype($this->lastModified, 'int');
        $this->value = json_decode($this->value, true);
    }
}