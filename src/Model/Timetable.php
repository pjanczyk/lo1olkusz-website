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

class Timetable
{
    /** @var string */
    public $class;
    /** @var int */
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
        if (!is_array($value) || count($value) !== 5)
            return 'Timetable must be an array of 5 days';

        foreach ($value as $dayNo=>$day) {

            if (!is_object($day))
                return "Day $dayNo is not represented by an object";

            foreach ($day as $hourNo=>$hour) {
                if (!is_numeric($dayNo))
                    return "Day $dayNo: \"$hourNo\" is not a correct number of an hour";

                if (!is_array($hour))
                    return "Day $dayNo: hour $hourNo is not represented by an array";

                foreach ($hour as $subjectNo=>$subject) {
                    if (!property_exists($subject, 'name'))
                        return "Day $dayNo: hour $hourNo: subject $subjectNo does not have \"name\" property";
                }
            }
        }

        return null;
    }
}