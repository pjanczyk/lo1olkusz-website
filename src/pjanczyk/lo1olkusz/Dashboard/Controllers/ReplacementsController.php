<?php

namespace pjanczyk\lo1olkusz\Dashboard\Controllers;

use pjanczyk\MVC\Controller;
use pjanczyk\lo1olkusz\ReplacementsTable;

class ReplacementsController extends Controller {

    public function index() {
        global $replacements;

        $model = new ReplacementsTable($this->db);
        $replacements = $model->getAll([ReplacementsTable::FIELD_DATE, ReplacementsTable::FIELD_CLASS, ReplacementsTable::FIELD_LAST_MODIFIED]);
        include 'views/replacements_list.php';
    }
}