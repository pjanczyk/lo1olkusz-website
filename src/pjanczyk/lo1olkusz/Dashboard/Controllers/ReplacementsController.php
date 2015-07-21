<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\MVC\Controller;
use pjanczyk\lo1olkusz\ReplacementsModel;

class ReplacementsController extends Controller {

    public function index() {
        global $replacements;

        $model = new ReplacementsModel($this->db);
        $replacements = $model->getAll([ReplacementsModel::FIELD_DATE, ReplacementsModel::FIELD_CLASS, ReplacementsModel::FIELD_LAST_MODIFIED]);
        include 'views/replacements_list.php';
    }
}