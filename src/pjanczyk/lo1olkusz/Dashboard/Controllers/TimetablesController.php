<?php
namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\lo1olkusz\Model\TimetablesModel;
use pjanczyk\MVC\Controller;

class TimetablesController extends Controller
{
    public function add()
    {
        $template = $this->includeTemplate('timetable_edit');
        $template->timetable = null;
        $template->render();
    }

    public function edit($class)
    {
        $model = new TimetablesModel($this->db);

        $template = $this->includeTemplate('timetable_edit');
        $template->timetable = $model->get($class);
        $template->render();
    }

    public function delete($class)
    {
        $model = new TimetablesModel($this->db);

        $timetable = $model->get($class);
        if ($timetable !== null) {
            $template = $this->includeTemplate('timetable_delete');
            $template->timetable = $timetable;
            $template->render();
        } else {
            $this->index();
        }
    }

    public function index()
    {
        $model = new TimetablesModel($this->db);
        $alerts = [];

        if (isset($_POST['edit'], $_POST['class'], $_POST['timetable'])) {
            if ($model->set($_POST['class'], $_POST['timetable'])) {
                $alerts[] = "Saved timetable of \"{$_POST['class']}\"";
            }
        } else if (isset($_POST['delete'], $_POST['class'])) {
            if ($model->delete($_POST['class'])) {
                $alerts[] = "Deleted timetable of \"{$_POST['class']}\"";
            }
        }

        $template = $this->includeTemplate('timetable_list');
        $template->timetables = $model->getAll([TimetablesModel::FIELD_CLASS, TimetablesModel::FIELD_LAST_MODIFIED]);
        $template->alerts = $alerts;
        $template->render();
    }
}