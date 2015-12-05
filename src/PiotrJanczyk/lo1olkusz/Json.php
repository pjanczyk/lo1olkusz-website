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

namespace PiotrJanczyk\lo1olkusz;

class Json
{
    public static function internalServerError()
    {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Interval Server Error']);
    }

    public static function badRequest()
    {
        header('HTTP/1.0 400 Bad Request');
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Bad Request']);
    }

    public static function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Not Found']);
    }

    public static function OK($array)
    {
        header('Content-Type: application/json');
        echo json_encode($array);
    }
}