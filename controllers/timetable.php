<?php

require_once 'classes/Database.php';
require_once 'classes/Timetables.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Timetables;

$model = new Timetables(Database::connect());

if (isset($_POST['class'], $_POST['timetable'], $_POST['delete'])) {
    if ($_POST['delete']) {
        $model->delete($_POST['class']);
    }
    else {
        $model->set($_POST['class'], $_POST['timetable']);
    }
}

$timetables = $model->listAll();

include 'views/timetable.php';