<?php
/** @var array $args */

require_once 'classes/Database.php';
require_once 'classes/TimetablesTable.php';

use pjanczyk\lo1olkusz\Database;
use pjanczyk\lo1olkusz\TimetablesTable;

date_default_timezone_set('Europe/Warsaw');

$model = new TimetablesTable(Database::connect());

if (isset($args[0])) {

    if ($args[0] == 'add') {
        $timetable = false;
        include 'views/timetable_edit.php';
        exit;
    }

    if ($args[0] == 'edit' && isset($args[1])) {
        $timetable = $model->get($args[1]);
        include 'views/timetable_edit.php';
        exit;
    }

    if ($args[0] == 'delete' && isset($args[1])) {
        $timetable = $model->get($args[1]);
        if ($timetable !== false) {
            include 'views/timetable_delete.php';
            exit;
        }
    }
}

$alerts = [];

if (isset($_POST['edit'], $_POST['class'], $_POST['timetable'])) {
    if ($model->set($_POST['class'], $_POST['timetable'])) {
        $alerts[] = "Saved timetable of \"{$_POST['class']}\"";
    }
}
else if (isset($_POST['delete'], $_POST['class'])) {
    if ($model->delete($_POST['class'])) {
        $alerts[] = "Deleted timetable of \"{$_POST['class']}\"";
    }
}

$timetables = $model->getAll([TimetablesTable::FIELD_CLASS, TimetablesTable::FIELD_LAST_MODIFIED]);

include 'views/timetable_list.php';