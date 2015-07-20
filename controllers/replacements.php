<?php

namespace pjanczyk\lo1olkusz\Dashboard;

require_once 'controllers/Controller.php';
require_once 'classes/ReplacementsTable.php';

use pjanczyk\lo1olkusz\ReplacementsTable;

class replacements extends Controller {

    public function index() {
        $model = new ReplacementsTable($this->db);
        global $replacements;
        $replacements = $model->getAll([ReplacementsTable::FIELD_DATE, ReplacementsTable::FIELD_CLASS, ReplacementsTable::FIELD_LAST_MODIFIED]);
        include 'views/replacements_list.php';
    }
}