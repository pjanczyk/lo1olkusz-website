<?php

/** @var array $args */

require_once 'classes/Database.php';
require_once 'classes/Timetables.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Timetables;

$model = new Timetables(Database::connect());

function jsonBadRequest() {
    header('HTTP/1.0 400 Bad request');
    header('Content-Type: application/json');
    echo json_encode([ 'error' => 'bad request' ]);
}

function jsonNotFound() {
    header('HTTP/1.0 404 Not Found');
    header('Content-Type: application/json');
    echo json_encode([ 'error' => 'not found' ]);
}

function jsonOK($array) {
    header('Content-Type: application/json');
    echo json_encode($array);
}

if (count($args) == 1) { # /api/timetables
    $timetables = $model->getAll([Timetables::FIELD_CLASS]);
    jsonOK($timetables);
}
else if (count($args) == 2) { # /api/timetables/<class>
    $timetable = $model->get($args[1]);
    if ($timetable !== false) {
        jsonOK($timetable);
    }
    else {
        jsonNotFound();
    }
}
else {
    jsonBadRequest();
}