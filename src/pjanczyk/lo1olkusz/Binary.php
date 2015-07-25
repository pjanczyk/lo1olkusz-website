<?php

namespace pjanczyk\lo1olkusz;


class Binary {

    public static function internalServerError() {
        header('HTTP/1.0 500 Internal Server Error');
        header('Content-Type: application/octet-stream');
    }

    public static function badRequest() {
        header('HTTP/1.0 400 Bad Request');
        header('Content-Type: application/octet-stream');
    }

    public static function notFound() {
        header('HTTP/1.0 404 Not Found');
        header('Content-Type: application/octet-stream');
    }

    public static function OK($binary) {
        header('Content-Type: application/octet-stream');
        echo 'PJ';
        echo $binary;
        echo 'PJ';
    }
}