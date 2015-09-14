<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\framework\Controller;
use pjanczyk\lo1olkusz\Models\ReplacementsModel;

class ReplacementsController extends Controller
{
    public function index()
    {
        $model = new ReplacementsModel($this->db);

        $template = $this->includeView('replacements_list');
        $template->replacements = $model->getAll([ReplacementsModel::FIELD_DATE, ReplacementsModel::FIELD_CLASS, ReplacementsModel::FIELD_LAST_MODIFIED]);
        $template->render();
    }

    public function view($date, $class) {
        $model = new ReplacementsModel($this->db);
        $replacements = $model->get($class, $date);

        if ($replacements != null) {
            $template = $this->includeView('replacements_view');
            $template->replacements = $replacements;
            $template->render();
        }
    }
}