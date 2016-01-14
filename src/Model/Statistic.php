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

class Statistic
{
    /** @var int */
    public $pageId;
    /** @var string */
    public $date;
    /** @var int */
    public $version;
    /** @var int */
    public $visits;
    /** @var int */
    public $uniqueVisits;

    public function __construct()
    {
        settype($this->pageId, 'int');
        settype($this->version, 'int');
        settype($this->visits, 'int');
        settype($this->uniqueVisits, 'int');
    }
}