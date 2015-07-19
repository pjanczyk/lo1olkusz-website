<?php

/** @var array $args */

require_once 'classes/Database.php';
require_once 'classes/TimetablesTable.php';
require_once 'classes/Json.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\TimetablesTable;
use pjanczyk\lo1olkusz\Json;

$model = new TimetablesTable(Database::connect());

if (count($args) == 1) { # /api/timetables
    $timetables = $model->getAll([TimetablesTable::FIELD_CLASS]);
    Json::OK($timetables);
}
else if (count($args) == 2) { # /api/timetables/<class>
    $timetable = $model->get($args[1]);
    if ($timetable !== false) {
        Json::OK($timetable);
    }
    else {
        Json::notFound();
    }
}
else {
    Json::badRequest();
}