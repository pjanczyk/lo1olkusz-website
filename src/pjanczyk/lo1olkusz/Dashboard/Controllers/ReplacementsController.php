<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\lo1olkusz\Model\ReplacementsModel;
use pjanczyk\MVC\Controller;

class ReplacementsController extends Controller
{

    public function index()
    {
        $model = new ReplacementsModel($this->db);

        $template = $this->includeTemplate('replacements_list');
        $template->replacements = $model->getAll([ReplacementsModel::FIELD_DATE, ReplacementsModel::FIELD_CLASS, ReplacementsModel::FIELD_LAST_MODIFIED]);
        $template->render();
    }
}