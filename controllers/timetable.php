<?php

require_once 'classes/Database.php';
require_once 'classes/Timetables.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\Timetables;

$model = new Timetables(Database::connect());

if (isset($args[0]) && ($args[0] == 'add' || $args[0] == 'edit')) {
    if (isset($args[1])) {
        $class = $args[1];
    }
    include 'views/timetable_edit.php';
    exit;
}

$alerts = [];

if (isset($_POST['edit'], $_POST['class'], $_POST['timetable'])) {
    if ($model->set($_POST['class'], $_POST['timetable'])) {
        $alerts[] = "Updated timetable of class \"{$_POST['class']}\"";
    }
}
else if (isset($_POST['delete'], $_POST['class'])) {
    if ($model->delete($_POST['class'])) {
        $alerts[] = "Deleted timetable of class \"{$_POST['class']}\"";
    }
}

$timetables = $model->listAll();

include 'views/timetable.php';