<?php

namespace pjanczyk\lo1olkusz\Dashboard;

require_once 'controllers/Controller.php';
require_once 'src/LuckyNumbersTable.php';

use pjanczyk\lo1olkusz\LuckyNumbersTable;

class LuckyNumbersController extends Controller {

    public function index() {
        $model = new LuckyNumbersTable($this->db);
        global $lns;
        $lns = $model->getAll([LuckyNumbersTable::FIELD_DATE,
            LuckyNumbersTable::FIELD_LAST_MODIFIED, LuckyNumbersTable::FIELD_LAST_MODIFIED]);
        include 'views/ln_list.php';
    }
}
