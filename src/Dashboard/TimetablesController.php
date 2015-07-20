<?php
namespace pjanczyk\lo1olkusz\Dashboard;

require_once 'src/Dashboard/Controller.php';
require_once 'src/TimetablesTable.php';

use pjanczyk\lo1olkusz\TimetablesTable;

class TimetablesController extends Controller {

    public function index() {
        global $alerts;
        global $timetables;

        $model = new TimetablesTable($this->db);
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
    }

    public function add() {
        global $timetable;

        $timetable = false;
        include 'views/timetable_edit.php';
    }

    public function edit($class) {
        global $timetable;

        $model = new TimetablesTable($this->db);
        $timetable = $model->get($class);
        include 'views/timetable_edit.php';
    }

    public function delete($class) {
        global $timetable;

        $model = new TimetablesTable($this->db);
        $timetable = $model->get($class);
        if ($timetable !== false) {
            include 'views/timetable_delete.php';
        }
        else {
            $this->index();
        }
    }
}