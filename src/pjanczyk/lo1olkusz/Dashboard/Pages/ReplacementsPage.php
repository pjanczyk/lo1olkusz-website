<?php

namespace pjanczyk\lo1olkusz\Dashboard\Pages;

use pjanczyk\framework\Page;
use pjanczyk\lo1olkusz\Model\ReplacementsModel;

class ReplacementsPage extends Page
{
    public function index()
    {
        $model = new ReplacementsModel($this->db);

        $template = $this->includeTemplate('replacements_list');
        $template->replacements = $model->getAll([ReplacementsModel::FIELD_DATE, ReplacementsModel::FIELD_CLASS, ReplacementsModel::FIELD_LAST_MODIFIED]);
        $template->render();
    }

    public function view($date, $class) {
        $model = new ReplacementsModel($this->db);

        $template = $this->includeTemplate('replacements_view');
        $template->replacements = $model->get($class, $date);
        $template->render();
    }
}