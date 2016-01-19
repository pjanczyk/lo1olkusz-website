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

namespace pjanczyk\lo1olkusz;

class Json
{
    public static function internalServerError()
    {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        echo json_encode(['error' => 'Interval Server Error']);
        exit;
    }

    public static function badRequest()
    {
        header('HTTP/1.0 400 Bad Request');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        $error = func_num_args() > 0 ? func_get_arg(0) : 'Bad Request';

        echo json_encode(['error' => $error]);
        exit;
    }

    public static function unauthorized()
    {
        header('HTTP/1.0 401 Unauthorized');
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    public static function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        echo json_encode(['error' => 'Not Found']);
        exit;
    }

    public static function OK($obj)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        echo json_encode($obj);
        exit;
    }
}