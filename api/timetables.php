<?php

/** @var array $args */

require_once 'classes/Database.php';
require_once 'classes/Timetables.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Timetables;

$model = new Timetables(Database::connect());

if (count($args) == 1) { # /api/timetables
    $timetables = $model->getAll([Timetables::FIELD_CLASS]);
    echo json_encode($timetables);
}
if (count($args) == 2) { # /api/timetables/<class>
    $class = $args[1];

    $timetable = $model->get($class);
    if ($timetable !== false) {
        echo json_encode($timetable);
    }
    else {
        echo json_encode([ 'error' => 'not found' ]);
    }
}