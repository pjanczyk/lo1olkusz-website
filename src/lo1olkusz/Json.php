<?php

namespace pjanczyk\lo1olkusz;


class Json {

    public static function internalServerError() {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: application/json');
        echo json_encode([ 'error' => 'Interval Server Error' ]);
    }

    public static function badRequest() {
        header('HTTP/1.0 400 Bad Request');
        header('Content-Type: application/json');
        echo json_encode([ 'error' => 'Bad Request' ]);
    }

    public static function notFound() {
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: application/json');
        echo json_encode([ 'error' => 'Not Found' ]);
    }

    public static function OK($array) {
        header('Content-Type: application/json');
        echo json_encode($array, JSON_FORCE_OBJECT);
    }
}