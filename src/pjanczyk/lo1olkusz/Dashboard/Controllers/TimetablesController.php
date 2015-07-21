<?php
namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\lo1olkusz\Models\TimetablesModel;
use pjanczyk\MVC\Controller;

class TimetablesController extends Controller {

    public function index() {
        global $alerts;
        global $timetables;

        $model = new TimetablesModel($this->db);
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

        $timetables = $model->getAll([TimetablesModel::FIELD_CLASS, TimetablesModel::FIELD_LAST_MODIFIED]);

        $this->includeTemplate('timetable_list');
    }

    public function add() {
        global $timetable;

        $timetable = false;
        $this->includeTemplate('timetable_edit');
    }

    public function edit($class) {
        global $timetable;

        $model = new TimetablesModel($this->db);
        $timetable = $model->get($class);
        $this->includeTemplate('timetable_edit');
    }

    public function delete($class) {
        global $timetable;

        $model = new TimetablesModel($this->db);
        $timetable = $model->get($class);
        if ($timetable !== false) {
            $this->includeTemplate('timetable_delete');
        }
        else {
            $this->index();
        }
    }
}